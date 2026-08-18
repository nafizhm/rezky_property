<?php
namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateNumberController;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\KavlingPeta;
use App\Models\MetodeBayar;
use App\Models\Pemasukan;
use App\Models\PengaturanMedia;
use App\Models\PindahUnit;
use App\Models\Piutang;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use TCPDF;
use Yajra\DataTables\DataTables;

class PindahUnitController extends Controller
{
    use LogAktivitasTrait;
    protected GenerateNumberController $generator;

    public function __construct(GenerateNumberController $generator)
    {
        $this->generator = $generator;
    }

    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {

            Carbon::setLocale('id');

            $data = PindahUnit::with([
                'customer',
                'kavlingLama.lokasi',
                'kavlingBaru.lokasi',
            ])
                ->whereHas('customer', function ($q) {
                    $q->where('stt_arsip', 0);
                })
                ->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('tgl_pindah', function ($row) {
                    return Carbon::parse($row->tgl_pindah)
                        ->translatedFormat('j F Y');
                })

                ->addColumn('lokasi_lama', function ($row) {
                    $lokasi  = $row->kavlingLama->lokasi->nama_kavling ?? '-';
                    $kavling = $row->kavlingLama->kode_kavling ?? '-';
                    return '<strong>' . $lokasi . '</strong><br>' . $kavling;
                })

                ->addColumn('lokasi_baru', function ($row) {
                    $lokasi  = $row->kavlingBaru->lokasi->nama_kavling ?? '-';
                    $kavling = $row->kavlingBaru->kode_kavling ?? '-';
                    return '<strong>' . $lokasi . '</strong><br>' . $kavling;
                })

                ->addColumn('action', function ($row) use ($permissions) {
                    $btn = '';

                    $btn .= '<a href="' . route('pindah-unit.kwitansi', $row->id) . '"
                    class="btn btn-primary btn-xs mr-1"
                    target="_blank">
                    Kwitansi
                </a>';

                //     $btn .= '<a href="' . route('pindah-unit.cetak-word', $row->id) . '"
                //     class="btn btn-success btn-xs mr-1">
                //     Cetak
                // </a>';

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . route('pindah-unit.destroy', $row->id) . '"
                        method="POST"
                        class="d-inline">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit"
                                class="delete-button btn btn-danger btn-xs mr-1">
                            Batalkan
                        </button>
                    </form>';
                    }

                    return $btn;
                })

                ->rawColumns(['lokasi_lama', 'lokasi_baru', 'action'])
                ->make(true);
        }

        $customers = Customer::select(['id', 'nama_lengkap'])
            ->whereIn('id_status_progres', [2, 4, 7])->where('stt_arsip', 0)
            ->get();

        $tanggalSekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $metodeBayar     = MetodeBayar::all();
        $kavBaru         = KavlingPeta::whereNull('id_customer')->get();
        $bankList        = Bank::all();

        return view(
            'admin.transaksi.pindah_unit.index',
            compact(
                'permissions',
                'customers',
                'bankList',
                'tanggalSekarang',
                'kavBaru',
                'metodeBayar'
            )
        );
    }

    public function detailCustomer($id_customer)
    {
        $customer = Customer::with('lokasi', 'kavling')->findOrFail($id_customer);

        $utj = Pemasukan::where('id_customer', $id_customer)
            ->where('id_kategori_transaksi', 1)->first();

        return response()->json([
            'success'  => true,
            'customer' => $customer,
            'utj'      => $utj->nominal ?? 0,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_pindah'        => 'required',
            'biaya_admin'       => 'nullable',
            'id_customer'       => 'required',
            'id_kavling_baru'   => 'required',
            'keterangan_pindah' => 'required',
            'id_bank'           => 'nullable',
            'id_metode_bayar'   => 'nullable',
            'lampiran_bukti'    => 'required_if:id_metode_bayar,2|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'tgl_pindah.required'        => 'Tanggal pindah wajib diisi.',
            'id_customer.required'       => 'Customer wajib dipilih.',
            'id_kavling_baru.required'   => 'Kavling baru wajib dipilih.',
            'keterangan_pindah.required' => 'Keterangan pindah wajib diisi.',
            'lampiran_bukti.required_if' => 'Lampiran bukti wajib diunggah.',
            'lampiran_bukti.file'        => 'Lampiran bukti harus berupa file.',
            'lampiran_bukti.mimes'       => 'Lampiran bukti harus berformat jpg, jpeg, png, atau pdf.',
            'lampiran_bukti.max'         => 'Ukuran lampiran bukti maksimal 2MB.',
        ]);

        DB::beginTransaction();
        try {

            $customer = Customer::with('lokasi')->find($request->id_customer);

            if ($request->hasFile('lampiran_bukti')) {
                $lampiran_bukti          = $request->file('lampiran_bukti');
                $ext                     = $lampiran_bukti->getClientOriginalExtension();
                $lampiran_bukti_filename = Str::random(25) . '.' . $ext;
                $lampiran_bukti->move(public_path('assets/keuangan/pemasukan/'), $lampiran_bukti_filename);
            }

            $biayaAdmin = str_replace('.', '', $request->biaya_admin ?? '');
            $pindahUnit = [
                'tgl_pindah'        => $request->tgl_pindah,
                'id_customer'       => $request->id_customer,
                'id_kavling_lama'   => $customer->id_kavling,
                'id_kavling_baru'   => $request->id_kavling_baru,
                'nominal_utj'       => str_replace('.', '', $request->nominal_utj),
                'biaya_admin'       => $biayaAdmin !== '' ? $biayaAdmin : 0,
                'keterangan_pindah' => $request->keterangan_pindah ?? '',
                'id_bank'           => $request->id_bank ?: 0,
                'id_metode_bayar'   => $request->id_metode_bayar ?: 0,
                'lampiran_bukti'    => $lampiran_bukti_filename ?? '',
            ];

            $pu = PindahUnit::create($pindahUnit);
            $this->logCreate('Pindah Unit', $pu->id);

            $lokasi = $customer->lokasi;

            $no_kwitansi = $this->generator->generateNomorDokumen(
                $lokasi,
                'no_kwitansi',
                Pemasukan::class
            );

            $pemasukan = [
                'id_bank'               => $request->id_bank ?: 0,
                'id_pindah_unit'        => $pu->id,
                'id_customer'           => $request->id_customer,
                'tanggal'               => $request->tgl_pindah,
                'nominal'               => $biayaAdmin !== '' ? $biayaAdmin : 0,
                'id_kategori_transaksi' => 20,
                'no_kwitansi'           => $no_kwitansi,
                'id_metode_bayar'       => $request->id_metode_bayar ?: 0,
                'lampiran'              => $lampiran_bukti_filename ?? '',
                'keterangan'            => $request->keterangan_pindah ?? '',
            ];

            Pemasukan::create($pemasukan);

            $kavlingBaru              = KavlingPeta::with('lokasi')->find($request->id_kavling_baru);
            $kavlingBaru->id_customer = $request->id_customer;
            $kavlingBaru->status      = 2;
            $kavlingBaru->save();

            $kavlingLama              = KavlingPeta::find($customer->id_kavling);
            $kavlingLama->id_customer = null;
            $kavlingLama->status      = 0;
            $kavlingLama->save();

            $customer->id_kavling = $request->id_kavling_baru;
            $customer->save();

            $tipe = (int) $kavlingBaru->tipe_bangunan;

            $pemasukan = Pemasukan::where('id_customer', $request->id_customer)
                ->where('id_kategori_transaksi', 1)
                ->first();

            if ($pemasukan) {
                $pemasukan->update([
                    'keterangan' => 'Booking Fee Rumah tipe ' .
                    $tipe . ' ' .
                    $kavlingBaru->lokasi->nama_kavling . ' Blok ' .
                    $kavlingBaru->kode_kavling,
                ]);
            }

            $piutang = Piutang::where('id_customer', $request->id_customer)->where('id_kategori_transaksi', 0)->first();

            if ($piutang) {
                $piutang->update([
                    'deskripsi' => 'Harga Rumah tipe ' .
                    $tipe . ' ' .
                    $kavlingBaru->lokasi->nama_kavling . ' Blok ' .
                    $kavlingBaru->kode_kavling,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Error storing data: ' . $e->getMessage());

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

            $data = PindahUnit::with([
                'customer',
                'kavlingLama.lokasi',
                'kavlingBaru.lokasi',
            ])->findOrFail($id);

            $customer    = $data->customer;
            $kavlingLama = $data->kavlingLama;
            $kavlingBaru = $data->kavlingBaru;

            if ($kavlingBaru) {
                $kavlingBaru->id_customer = null;
                $kavlingBaru->status      = 0;
                $kavlingBaru->save();
            }

            if ($kavlingLama) {
                $kavlingLama->id_customer = $customer->id;
                $kavlingLama->status      = 2;
                $kavlingLama->save();
            }

            if ($customer && $kavlingLama) {
                $customer->id_kavling = $kavlingLama->id;
                $customer->save();
            }

            Pemasukan::where('id_pindah_unit', $data->id)->delete();

            $tipe = (int) ($kavlingLama->tipe_bangunan ?? 0);

            $pemasukanBooking = Pemasukan::where('id_customer', $customer->id)
                ->where('id_kategori_transaksi', 1)
                ->first();

            if ($pemasukanBooking && $kavlingLama) {
                $pemasukanBooking->update([
                    'keterangan' => 'Booking Fee Rumah tipe ' .
                    $tipe . ' ' .
                    $kavlingLama->lokasi->nama_kavling . ' Blok ' .
                    $kavlingLama->kode_kavling,
                ]);
            }

            $piutang = Piutang::where('id_customer', $customer->id)->where('id_kategori_transaksi', 0)->first();

            if ($piutang && $kavlingLama) {
                $piutang->update([
                    'deskripsi' => 'Harga Rumah tipe ' .
                    $tipe . ' ' .
                    $kavlingLama->lokasi->nama_kavling . ' Blok ' .
                    $kavlingLama->kode_kavling,
                ]);
            }

            if ($data->lampiran_bukti) {
                $path = public_path('assets/keuangan/pemasukan/' . $data->lampiran_bukti);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $this->logDelete('Pindah Unit', $data->id);
            $data->delete();

            DB::commit();

            return response()->json([
                'status' => true,
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
            ], 500);
        }
    }

    public function cetakWord($id)
    {
        $data = PindahUnit::findOrFail($id);

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $phpWord->addTitleStyle(1, [
            'bold'      => true,
            'size'      => 18,
            'alignment' => Jc::CENTER,
        ]);

        $section->addText("Data Pindah Unit", [
            'bold' => true,
            'size' => 18,
        ], [
            'alignment'  => Jc::CENTER,
            'spaceAfter' => 200,
        ]);

        $table = $section->addTable([
            'borderSize'  => 6,
            'borderColor' => '999999',
            'cellMargin'  => 80,
        ]);

        $addRow = function ($label, $value) use ($table) {
            $table->addRow();
            $table->addCell(3000)->addText($label, ['bold' => true]);
            $table->addCell(8000)->addText($value);
        };

        $addRow("Tanggal Pindah", $data->tgl_pindah);
        $addRow("Nama Customer", $data->nama_customer);
        $addRow("No KTP", $data->no_ktp);
        $addRow("Alamat", $data->alamat);
        $addRow("Kavling Lama", $data->kode_kavling_lama);
        $addRow("Kavling Baru", $data->kode_kavling_baru);
        $addRow("Nominal UTJ", "Rp. " . number_format($data->nominal_utj, 0, ',', '.'));
        $addRow("Biaya Admin", "Rp. " . number_format($data->biaya_admin, 0, ',', '.'));
        $addRow("Metode Bayar", strtoupper($data->metode_bayar));
        // $addRow("No Rekening", $data->no_rekening);
        // $addRow("Jumlah Bayar", "Rp. " . number_format($data->jumlah_bayar, 0, ',', '.'));
        $addRow("Keterangan", $data->keterangan_pindah ?? '-');

        $fileName = 'pindah_unit_' . $data->id . '.docx';
        $tempFile = storage_path($fileName);

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    public function cetakKwitansi($id)
    {
         Carbon::setLocale('id');

    $pindah = PindahUnit::with([
        'customer',
        'kavlingLama',
        'kavlingBaru'
    ])->findOrFail($id);

    $customer     = $pindah->customer;
    $kavlingLama  = $pindah->kavlingLama;
    $kavlingBaru  = $pindah->kavlingBaru;

    $pdf = new TCPDF('P', 'mm', 'A4');
    $pdf->SetTitle('Rekap Pindah Unit - ' . $customer->nama_lengkap);
    $pdf->AddPage();

      $mediaRekap = PengaturanMedia::where('jenis_data', 'Logo Rekap')->first();
        $pathRekap  = null;
        if ($mediaRekap && $mediaRekap->nama_file) {
            $pathRekap = public_path('config_media/' . $mediaRekap->nama_file);
        }

         if ($pathRekap && file_exists($pathRekap)) {
            $pdf->Image($pathRekap, 15, 12, 30, 25);
        }

    $pdf->SetFont('helvetica', 'B', 25);
    $pdf->SetTextColor(0, 51, 102);
    $pdf->Cell(0, 7, 'PT. ALAM INDAH SELALU', 0, 1, 'C');

    $pdf->SetFont('Times', '', 11);
    $pdf->SetTextColor(218, 0, 0);
    $pdf->Cell(0, 6, 'KONTRAKTOR - DEVELOPER', 0, 1, 'C');

    $pdf->SetFont('Times', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(
            0,
            0,
            'Komplek Ruko Hawai Garden Blok B No. 2 Kelurahan Belian Batan - Center',
            0,
            'C'
        );
        $pdf->SetX(10);
        $pdf->cell(0, 0, 'Telp. 0778 - 4173387', 0, 1, 'C');

    $pdf->Ln(5);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.7);
    $pdf->Line(10, 42, 200, 42);

    $pdf->SetLineWidth(0.3);
    $pdf->Line(10, 41, 200, 41);

    $pdf->Ln(8);
    $pdf->SetFont('Times', 'B', 10);
    $pdf->SetTextColor(218, 0, 0);
    $pdf->Cell(190, 8, 'TABEL REKAP PINDAH UNIT', 0, 1, 'C');
    $pdf->Ln(3);

    $pdf->SetFont('Times', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $startY = $pdf->GetY();

    $pdf->SetXY(10, $startY);
    $pdf->Cell(30, 6, 'Nama', 0, 0);
    $pdf->Cell(3, 6, ':', 0, 0);
    $pdf->Cell(60, 6, strtoupper($customer->nama_lengkap), 0, 1);

    $pdf->SetX(10);
    $pdf->Cell(30, 6, 'No. KTP', 0, 0);
    $pdf->Cell(3, 6, ':', 0, 0);
    $pdf->Cell(60, 6, $customer->nik ?? '-', 0, 1);

    $pdf->SetX(10);
    $pdf->Cell(30, 6, 'Alamat', 0, 0);
    $pdf->Cell(3, 6, ':', 0, 0);
    $pdf->Cell(60, 6, $customer->alamat_domisili ?? '-', 0, 1);

    $pdf->SetXY(90, $startY);
    $pdf->Cell(30, 6, 'Kavling Lama', 0, 0);
    $pdf->Cell(3, 6, ':', 0, 0);
    $pdf->Cell(40, 6, $kavlingLama->kode_kavling ?? '-', 0, 1);

    $pdf->SetX(90);
    $pdf->Cell(30, 6, 'Kavling Baru', 0, 0);
    $pdf->Cell(3, 6, ':', 0, 0);
    $pdf->Cell(40, 6, $kavlingBaru->kode_kavling ?? '-', 0, 1);

    $pdf->SetXY(145, $startY);
    $pdf->Cell(35, 6, 'Uang Tanda Jadi', 0, 0);
    $pdf->Cell(3, 6, ': Rp.', 0, 0);
    $pdf->Cell(20, 6, number_format($pindah->nominal_utj ?? 0, 0, ',', '.'), 0, 1, 'R');

    $pdf->SetX(145);
    $pdf->Cell(35, 6, 'Biaya Admin', 0, 0);
    $pdf->Cell(3, 6, ': Rp.', 0, 0);
    $pdf->Cell(20, 6, number_format($pindah->biaya_admin ?? 0, 0, ',', '.'), 0, 1, 'R');

    $pdf->Ln(10);
    $pdf->SetFont('Times', 'B', 9);
    $pdf->SetFillColor(211, 236, 230);

    $pdf->Cell(8, 7, 'No', 1, 0, 'C', true);
    $pdf->Cell(35, 7, 'Tanggal', 1, 0, 'C', true);
    $pdf->Cell(95, 7, 'Keterangan', 1, 0, 'C', true);
    $pdf->Cell(42, 7, 'Nominal', 1, 1, 'C', true);

    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(8, 7, '1', 1, 0, 'C');
    $pdf->Cell(35, 7, Carbon::parse($pindah->tgl_pindah)->translatedFormat('d F Y'), 1, 0, 'C');
    $pdf->Cell(95, 7, $pindah->keterangan_pindah ?? '-', 1, 0, 'L');
    $pdf->Cell(6, 7, 'Rp.', 'TBL', 0);
    $pdf->Cell(
        36,
        7,
        number_format(($pindah->nominal_utj + $pindah->biaya_admin), 0, ',', '.'),
        'TBR',
        1,
        'R'
    );

    $pdf->Ln(15);
    $pdf->Cell(60, 6, 'Mengetahui', 0, 0, 'C');
    $pdf->Cell(70, 6, '', 0, 0);
    $pdf->Cell(60, 6, 'Mengetahui', 0, 1, 'C');

    $pdf->Ln(20);
    $pdf->Cell(60, 6, 'ADMIN KEUANGAN', 0, 0, 'C');
    $pdf->Cell(70, 6, '', 0, 0);
    $pdf->Cell(60, 6, 'ADMIN DUA', 0, 1, 'C');

    $pdf->Output();
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
