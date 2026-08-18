<?php
namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Bank;
use App\Models\BankKPR;
use App\Models\Customer;
use App\Models\PembelianCancel;
use App\Models\Pengeluaran;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TCPDF;
use Yajra\DataTables\DataTables;

class PembelianCancelController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = PembelianCancel::with('customer.kavling')->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tgl_batal', function ($row) {
                    return Carbon::parse($row->tgl_batal)->translatedFormat('d F Y');
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $btn = '';

                    if ($permissions['hapus']) {
                        $kavling = $row->customer?->kavling;

                        if ($kavling && $kavling->id_customer != null && $kavling->status != 0) {
                            return '
            <button type="button"
                class="btn btn-danger btn-sm"
                disabled
                title="Kavling sudah milik orang lain">
                Batalkan
            </button>
        ';
                        }

                        return '
        <form action="' . route('pembelian-cancel.destroy', $row->id) . '"
            method="POST"
            class="d-inline">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit"
                class="delete-button btn btn-danger btn-sm">
                Batalkan
            </button>
        </form>
    ';
                    }

                    return $btn;
                })

                ->rawColumns(['no_telp', 'action', 'nama_customer'])
                ->make(true);
        }

        $customers = Customer::select(['id', 'nama_lengkap'])
            ->whereIn('id_status_progres', [2, 4, 7])->where('stt_arsip', 0)
            ->get();

        $tanggalSekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $rekeningList   = Bank::all();
        $bankTujuanList = BankKPR::all();

        return view('admin.transaksi.pembelian_cancel.index', compact('permissions', 'customers', 'rekeningList', 'tanggalSekarang', 'bankTujuanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_batal'        => 'required',
            'id_customer'      => 'required',
            'keterangan_batal' => 'required',
            'biaya_admin'      => 'required',
            'jumlah_bayar'     => 'required',
            'id_bank'          => 'nullable',
            'id_bank_tujuan'   => 'nullable',
            'no_rekening'      => 'nullable',
            'atas_nama'        => 'nullable',
            'lampiran_bukti'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'tgl_batal.required'        => 'Tanggal Pembatalan wajib diisi.',
            'id_customer.required'      => 'Customer wajib dipilih.',
            'keterangan_batal.required' => 'Keterangan Pembatalan wajib diisi.',
            'biaya_admin.required'      => 'Biaya Admin wajib diisi.',
            'jumlah_bayar.required'     => 'Jumlah Bayar wajib diisi.',
            'lampiran_bukti.file'       => 'Lampiran Bukti harus berupa file.',
            'lampiran_bukti.mimes'      => 'Lampiran Bukti harus berupa file dengan format: jpg, jpeg, png, pdf.',
            'lampiran_bukti.max'        => 'Ukuran maksimum Lampiran Bukti adalah 2MB.',
        ]);

        DB::beginTransaction();
        try {

            $customer = Customer::with('kavling', 'lokasi')->find($request->id_customer);

            $lampiran_bukti_filename = null;
            if ($request->hasFile('lampiran_bukti')) {
                $lampiran_bukti          = $request->file('lampiran_bukti');
                $ext                     = $lampiran_bukti->getClientOriginalExtension();
                $lampiran_bukti_filename = Str::random(25) . '.' . $ext;
                $lampiran_bukti->move(public_path('assets/keuangan/pengeluaran/'), $lampiran_bukti_filename);
            }

            $pc = PembelianCancel::create([
                'tgl_batal'        => $request->tgl_batal,
                'id_customer'      => $request->id_customer,
                'keterangan_batal' => $request->keterangan_batal,
                'biaya_admin'      => str_replace(['.', ','], ['', ''], $request->biaya_admin),
                'jumlah_bayar'     => str_replace(['.', ','], ['', ''], $request->jumlah_bayar),
                'id_bank'          => $request->id_bank,
                'id_bank_tujuan'   => $request->id_bank_tujuan,
                'no_rekening'      => $request->no_rekening,
                'atas_nama'        => $request->atas_nama,
                'lampiran_bukti'   => $lampiran_bukti_filename,
            ]);

            $this->logCreate('Pembelian Cancel', $pc->id);

            Pengeluaran::create([
                'id_pembelian_cancel'   => $pc->id,
                'tanggal'               => $request->tgl_batal,
                'id_bank'               => $request->id_bank ?: 0,
                'nominal'               => str_replace(['.', ','], ['', ''], $request->jumlah_bayar),
                'id_kategori_transaksi' => 18,
                'id_metode_bayar'       => 2,
                'lampiran'              => $lampiran_bukti_filename ?? '',
                'keterangan'            => 'Pembatalan Pembelian Unit ' . $customer->lokasi->nama_kavling . ' - ' . $customer->kavling->kode_kavling . ' atas nama ' . $customer->nama_lengkap,
            ]);

            $customer->kavling->status      = 0;
            $customer->kavling->id_customer = null;
            $customer->kavling->save();

            $customer->stt_arsip  = 1;
            $customer->id_kavling = null;
            $customer->save();

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $data = PembelianCancel::with('customer.kavling')->findOrFail($id);

            $customer = $data->customer;
            $kavling  = $customer?->kavling;

            Pengeluaran::where('id_pembelian_cancel', $data->id)->delete();

            if ($customer) {
                $customer->stt_arsip = 0;
                $customer->save();
            }

            if ($kavling && $customer) {
                $kavling->status      = 2;
                $kavling->id_customer = $customer->id;
                $kavling->save();

                $customer->id_kavling = $kavling->id;
                $customer->save();
            }

            if ($data->lampiran_bukti) {
                $path = public_path('assets/keuangan/pengeluaran/' . $data->lampiran_bukti);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $this->logDelete('Pembelian Cancel', $data->id);
            $data->delete();

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
            ], 500);
        }
    }

    public function cetakKwitansi($id)
    {
        $data = PembelianCancel::with('kavling')->findOrFail($id);

        $width  = 210;
        $height = 120;
        $pdf    = new TCPDF('L', 'mm', [$width, $height]);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        $bgPath = public_path('assets/img/bg_kwitansi.jpg');
        if (file_exists($bgPath)) {
            $pdf->Image($bgPath, 0, 0, $width, $height);
        }

        // Header
        // $pdf->SetFont('Times', 'B', 10);
        // $pdf->SetTextColor(0, 0, 0);
        // $pdf->SetXY(40, 10);
        // $pdf->Cell(0, 7, 'PT. HAMZAH MAJU BERSAMA', 0, 2, 'L');

        // $pdf->SetTextColor(0, 176, 240);
        // $pdf->SetXY(40, 15);
        // $pdf->Cell(0, 7, 'developer & kontraktor', 0, 2, 'L');

        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetFont('Times', 'B', 10);

        $pdf->SetXY(15, 29);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(0, 18, $data->no_kwitansi ?? '-', 0, 1, 'L');

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Times', '', 12);
        $pdf->SetXY(5, 36);
        $pdf->Cell(55, 5, '', 0, 0, 'L');
        $pdf->Cell(90, 15, ($data->nama_customer ?? '-'), 0, 1, 'L');

        $pdf->SetXY(5, 48);
        $pdf->Cell(55, 0, '', 0, 0, 'L');
        $pdf->Cell(90, 0, $this->terbilang($data->jumlah_bayar) . ' Rupiah', 0, 1, 'L');

        $pdf->SetXY(5, 56);
        $pdf->Cell(55, 5, '', 0, 0, 'L');
        $pdf->MultiCell(90, 3, ($data->keterangan_batal ?? '-'), 0, 'L');

        $pdf->SetXY(5, 60);
        $pdf->Cell(55, 5, '', 0, 0, 'L');
        $pdf->Cell(90, 10, $data->kavling->kode_kavling ?? '-', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Text(30, 80, number_format($data->jumlah_bayar, 0, ',', '.') . ',-');

        $metode    = strtoupper($data->metode_bayar ?? '-');
        $checkIcon = public_path('check-solid.png');
        $checkSize = 5;

        if (file_exists($checkIcon)) {
            switch ($metode) {
                case 'CASH':
                    $pdf->Image($checkIcon, 11.5, 88, $checkSize);
                    break;

                case 'TRANSFER':
                    $pdf->Image($checkIcon, 28, 88, $checkSize);
                    break;

                case 'CHEQUE':
                    $pdf->Image($checkIcon, 53, 88, $checkSize);
                    break;

                case 'BILYET GIRO':
                    $pdf->Image($checkIcon, 74, 88, $checkSize);
                    break;
            }
        }

        $pdf->Image(public_path('assets/img/ttd_direktur.jpg'), 153, 90, 25);
        $pdf->Ln(-15);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(155, 0, '', 0, 0, 'C');
        $pdf->Cell(70, 40, Carbon::parse($data->tgl_pindah)->translatedFormat('d F Y'), 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetXY(14, 101);
        $pdf->Cell(0, 0, $data->no_rekening ?? '-', 0, 'L');

        $pdf->Output('Pindah-unit_' . $data->id_pindah . '.pdf', 'I');
        exit;
    }

    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca  = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $hasil = "";

        if ($angka < 12) {
            $hasil = " " . $baca[$angka];
        } elseif ($angka < 20) {
            $hasil = $this->terbilang($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            $hasil = $this->terbilang($angka / 10) . " Puluh" . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $hasil = " Seratus" . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = $this->terbilang($angka / 100) . " Ratus" . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = " Seribu" . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $hasil = $this->terbilang($angka / 1000) . " Ribu" . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $hasil = $this->terbilang($angka / 1000000) . " Juta" . $this->terbilang($angka % 1000000);
        }

        return trim($hasil);
    }
}
