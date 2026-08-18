<?php
namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateNumberController;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\GantiNama;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\MetodeBayar;
use App\Models\Pemasukan;
use App\Models\PersyaratanLegal;
use App\Models\Piutang;
use App\Models\UploudFile;
use App\Models\Wawancara;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TCPDF;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\LogAktivitasTrait;

class GantiNamaController extends Controller
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
        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = GantiNama::with('customerLama', 'customerBaru.lokasi', 'customerBaru.kavling')
                ->whereHas('customerBaru', function ($q) {
                    $q->where('stt_arsip', 0);
                })
                ->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tgl_ganti', function ($row) {
                    return Carbon::parse($row->tgl_ganti)->translatedFormat('d F Y');
                })
                ->addColumn('customer_lama', function ($row) {
                    return $row->customerLama->nama_lengkap ?? '-';
                })
                ->addColumn('customer_baru', function ($row) {
                    return $row->customerBaru->nama_lengkap ?? '-';
                })
                ->addColumn('lokasi_rumah', function ($row) {
                    $lokasi  = $row->customerBaru->lokasi->nama_kavling ?? '-';
                    $kavling = $row->customerBaru->kavling->kode_kavling ?? '-';
                    return '<strong>' . $lokasi . '</strong><br>' . $kavling;
                })
                ->editColumn('biaya_ganti_nama', function ($row) {
                    return '
        <div class="d-flex justify-content-between harga-format w-100">
            <span>Rp.</span>
            <span>' . number_format($row->biaya_ganti_nama, 0, ',', '.') . '</span>
        </div>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    if ($permissions['hapus']) {
                        return '
                <form action="' . route('ganti-nama.destroy', $row->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-button btn btn-danger btn-sm">Kembalikan</button>
                </form>
            ';
                    }
                    return '';
                })
                ->rawColumns(['lokasi_rumah', 'action', 'biaya_ganti_nama'])
                ->make(true);

        }

        $customers = Customer::select(['id', 'nama_lengkap'])
            ->whereIn('id_status_progres', [2, 4, 7])->where('stt_arsip', 0)
            ->get();

        $rekeningList = Bank::all();
        $metodeList   = MetodeBayar::all();

        $tanggalSekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        return view('admin.transaksi.ganti_nama.index', compact(
            'customers',
            'permissions',
            'rekeningList',
            'tanggalSekarang',
            'metodeList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_ganti'        => 'required',
            'id_customer_lama' => 'required',
            'biaya_ganti_nama' => 'nullable',
            'id_metode_bayar'  => 'nullable',
            'id_bank'          => 'nullable',
            'lampiran_bukti'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan_ganti' => 'required',
            'nama_lengkap'     => 'required',
            'nik'              => 'required',
            'tempat_lahir'     => 'required',
            'tgl_lahir'        => 'required',
            'jenis_kelamin'    => 'required',
            'no_telp'          => 'required',
            'alamat_domisili'  => 'required',
        ], [
            'tgl_pindah.required'        => 'Tanggal pindah harus diisi',
            'id_customer_lama.required'  => 'Customer lama harus diisi',
            'keterangan_ganti.required'  => 'Keterangan ganti harus diisi',
            'nama_lengkap.required'      => 'Nama lengkap harus diisi',
            'nik.required'               => 'NIK harus diisi',
            'tempat_lahir.required'      => 'Tempat lahir harus diisi',
            'tgl_lahir.required'         => 'Tanggal lahir harus diisi',
            'jenis_kelamin.required'     => 'Jenis kelamin harus diisi',
            'no_telp.required'           => 'No. Telp harus diisi',
            'alamat_domisili.required'   => 'Alamat domisili harus diisi',
        ]);

        DB::beginTransaction();
        try {

            $customerLama = Customer::with('lokasi')->findOrFail($request->id_customer_lama);

            $customerBaru = Customer::create([
                'kode_customer'     => $customerLama->kode_customer,
                'tanggal_verif'     => $customerLama->tanggal_verif,
                'id_lokasi'         => $customerLama->id_lokasi,
                'id_kavling'        => $customerLama->id_kavling,
                'id_status_progres' => $customerLama->id_status_progres,
                'status_pernikahan' => null,
                'nama_p'            => null,
                'no_bpjs_kes'       => null,
                'nama_saudara'      => null,
                'no_telp_saudara'   => null,
                'jenis_perumahan'   => $customerLama->jenis_perumahan,
                'id_marketing'      => $customerLama->id_marketing,
                'jenis_pembelian'   => $customerLama->jenis_pembelian,
                'an_surat_cash'     => $customerLama->an_surat_cash,
                'termin_x_cash_b'   => $customerLama->termin_x_cash_b,
                'stt_arsip'         => 0,

                'nama_lengkap'      => $request->nama_lengkap,
                'nik'               => $request->nik,
                'nik_p'             => $request->nik_p,
                'tempat_lahir'      => $request->tempat_lahir,
                'tgl_lahir'         => $request->tgl_lahir,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'no_telp'           => $request->no_telp,
                'email'             => $request->email,
                'npwp'              => $request->npwp,
                'alamat_domisili'   => $request->alamat_domisili,
                'alamat_ktp'        => $request->alamat_ktp,
                'pekerjaan'         => $request->pekerjaan,
            ]);

            $customerLama->stt_arsip = 1;
            $customerLama->save();

            $customerLama->kavling->id_customer = $customerBaru->id;
            $customerLama->kavling->save();

            if ($request->hasFile('lampiran_bukti')) {
                $lampiran_bukti          = $request->file('lampiran_bukti');
                $ext                     = $lampiran_bukti->getClientOriginalExtension();
                $lampiran_bukti_filename = Str::random(25) . '.' . $ext;
                $lampiran_bukti->move(public_path('assets/keuangan/pemasukan/'), $lampiran_bukti_filename);
            }

            $biayaGantiNama = str_replace('.', '', $request->biaya_ganti_nama ?? '');
            $gn = GantiNama::create([
                'tgl_ganti'        => $request->tgl_ganti,
                'id_customer_lama' => $request->id_customer_lama,
                'id_customer_baru' => $customerBaru->id,
                'biaya_ganti_nama' => $biayaGantiNama !== '' ? $biayaGantiNama : 0,
                'keterangan_ganti' => $request->keterangan_ganti ?? 'Ganti nama a/n ' . $customerLama->nama_lengkap,
                'lampiran_bukti'   => $lampiran_bukti_filename ?? '',
            ]);

            $this->logCreate('Ganti Nama', $gn->id);

            Pemasukan::where('id_customer', $customerLama->id)
                ->update(['id_customer' => $customerBaru->id]);

            Piutang::where('id_customer', $customerLama->id)
                ->update(['id_customer' => $customerBaru->id]);

            Wawancara::where('id_customer', $customerLama->id)
                ->update(['id_customer' => $customerBaru->id]);

            PersyaratanLegal::where('id_customer', $customerLama->id)
                ->update(['id_customer' => $customerBaru->id]);

            UploudFile::where('id_customer', $customerLama->id)
                ->update(['id_customer' => $customerBaru->id]);

            $lokasi = $customerLama->lokasi;

            $no_kwitansi = $this->generator->generateNomorDokumen(
                $lokasi,
                'no_kwitansi',
                Pemasukan::class
            );

            $pemasukan = [
                'id_bank'               => $request->id_bank ?: 0,
                'id_ganti_nama'         => $gn->id,
                'id_customer'           => $request->id_customer_lama,
                'tanggal'               => $request->tgl_ganti,
                'no_kwitansi'           => $no_kwitansi,
                'nominal'               => $biayaGantiNama !== '' ? $biayaGantiNama : 0,
                'id_kategori_transaksi' => 19,
                'id_metode_bayar'       => $request->id_metode_bayar ?: 0,
                'lampiran'              => $lampiran_bukti_filename ?? '',
                'keterangan'            => $request->keterangan_ganti ?? '',
            ];

            Pemasukan::create($pemasukan);

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing ganti nama: ' . $e->getMessage());
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

            $gn = GantiNama::findOrFail($id);

            $customerLama = Customer::findOrFail($gn->id_customer_lama);
            $customerBaru = Customer::findOrFail($gn->id_customer_baru);

            $pemasukan = Pemasukan::where('id_ganti_nama', $gn->id)->get();

            foreach ($pemasukan as $item) {
                if ($item->lampiran && file_exists(public_path('assets/keuangan/pemasukan/' . $item->lampiran))) {
                    unlink(public_path('assets/keuangan/pemasukan/' . $item->lampiran));
                }
            }

            Pemasukan::where('id_ganti_nama', $gn->id)->delete();

            Pemasukan::where('id_customer', $customerBaru->id)
                ->update(['id_customer' => $customerLama->id]);

            Piutang::where('id_customer', $customerBaru->id)
                ->update(['id_customer' => $customerLama->id]);

            Wawancara::where('id_customer', $customerBaru->id)
                ->update(['id_customer' => $customerLama->id]);

            PersyaratanLegal::where('id_customer', $customerBaru->id)
                ->update(['id_customer' => $customerLama->id]);

            UploudFile::where('id_customer', $customerBaru->id)
                ->update(['id_customer' => $customerLama->id]);

            $kavling = KavlingPeta::where('id_customer', $customerBaru->id)->first();
            if ($kavling) {
                $kavling->id_customer = $customerLama->id;
                $kavling->save();
            }

            $customerLama->stt_arsip = 0;
            $customerLama->save();

            $customerBaru->delete();
            $this->logDelete('Ganti Nama', $gn->id);
            $gn->delete();

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

    public function cetak($id)
    {
        $pembayaran = Pemasukan::with(['customer'])->where('id_customer', $id)->where('keterangan', 'GANTI NAMA')->firstOrFail();
        $nasabah    = $pembayaran->customer;
        $lokasi     = LokasiKavling::where('id', $nasabah->id_lokasi ?? null)->first();
        $kavling    = KavlingPeta::where('id', $nasabah->id_kavling ?? null)->first();
        $gantiNama  = GantiNama::where('id_customer_baru', $nasabah->id)->first();

        $noKwitansi      = $pembayaran->no_kwitansi ?? '-';
        $nama            = $nasabah->nama_lengkap ?? '-';
        $alamat          = $nasabah->alamat ?? '-';
        $jumlah          = $pembayaran->jumlah ?? 0;
        $terbilang       = '#' . strtoupper($this->terbilang($jumlah)) . ' Rupiah#';
        $namaKavling     = $lokasi->nama_kavling ?? '-';
        $tipe            = $kavling->tipe_bangunan ?? '-';
        $blokNomor       = $kavling->kode_kavling ?? '-';
        $rumahId         = $kavling->id_rumah_sikumbang ?? '-';
        $kotaTtd         = $lokasi->kota_penandatangan ?? '-';
        $tanggal         = $pembayaran->tanggal ? Carbon::parse($pembayaran->tanggal)->translatedFormat('d F Y') : '-';
        $jenisPembayaran = $gantiNama->keterangan_ganti ?? 'Cicilan Pribadi';
        $metodeBayar     = $pembayaran->metode_bayar ?? 'CASH';

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Kwitansi');
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->SetMargins(20, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Ln(40);

        $pdf->SetFont('Helvetica', 'BU', 16);
        $pdf->Cell(170, 6, 'TANDA TERIMA', 0, 1, 'C');

        $pdf->SetFont('Helvetica', 'I', 12);
        $pdf->Cell(170, 6, $noKwitansi, 0, 1, 'C');

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(100, 5, 'Sudah diterima dari : ', 0, 1, 'L');

        $pdf->Cell(20, 5, '', 0, 0, 'L');
        $pdf->Cell(25, 5, 'Nama', 0, 0, 'L');
        $pdf->Cell(60, 5, ' : ' . $nama, 0, 1, 'L');

        $pdf->Cell(20, 5, '', 0, 0, 'L');
        $pdf->Cell(25, 5, 'Alamat', 0, 0, 'L');
        $pdf->Cell(100, 5, ' : ' . $alamat, 0, 1, 'L');

        $pdf->Ln(3);
        $pdf->Cell(20, 5, 'Uang sejumlah Rp. ' . number_format($jumlah, 0, ',', '.') . ' (' . $terbilang . ')', 0, 1, 'L');
        $pdf->Cell(20, 5, 'Untuk pembayaran ' . $jenisPembayaran . ' : ', 0, 1, 'L');

        $pdf->Ln(1);
        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Perumahan', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $namaKavling, 0, 1, 'L');

        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Type', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $tipe, 0, 1, 'L');

        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Blok / Nomor', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $blokNomor, 0, 1, 'L');

        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Rumah ID', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $rumahId, 0, 1, 'L');

        // $pdf->Cell(20, 4, '', 0, 0, 'L');
        // $pdf->Cell(25, 4, 'Harga Jual', 0, 0, 'L');
        // $pdf->Cell(60, 4, ' : Rp. ' . number_format($hargaJual, 0, ',', '.'), 0, 1, 'L');

        $pdf->SetFont('Helvetica', 'B', 8.5);
        $pdf->Cell(20, 4, '', 0, 1, 'L');
        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(100, 4, '', 0, 0, 'L');
        $pdf->Cell(55, 4, $kotaTtd . ', ' . $tanggal, 0, 1, 'C');

        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 8.5);
        $pdf->Cell(35, 5, 'Keterangan : ', 0, 0, 'L');
        $pdf->Cell(45, 5, 'Kasir', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Penyetor', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Customer Service', 0, 1, 'C');

        $pdf->Cell(35, 5, $metodeBayar, 0, 0, 'L');
        $pdf->Cell(100, 5, '', 0, 0, 'L');
        $pdf->Ln(20);

        $pdf->SetLineWidth(0.2);
        $pdf->Line(60, 135, 95, 135);
        $pdf->Line(105, 135, 140, 135);
        $pdf->Line(150, 135, 185, 135);

        $pdf->SetFont('Helvetica', 'I', 8.5);
        $pdf->Cell(20, 5, 'NB : Kwitansi ini sah, apabila ada cap perusahaan dan tanda tangan kasir.', 0, 1, 'L');

        return response($pdf->Output('kwitansi.pdf', 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    private function bulanRomawi($bulan)
    {
        $romawi = [
            1  => 'I',
            2  => 'II',
            3  => 'III',
            4  => 'IV',
            5  => 'V',
            6  => 'VI',
            7  => 'VII',
            8  => 'VIII',
            9  => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $romawi[(int) $bulan] ?? '';
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

    public function getCustomer($id_customer)
    {
        $customer = Customer::with("kavling", "kavling.lokasi")->findOrFail($id_customer);

        return $customer;
    }
}
