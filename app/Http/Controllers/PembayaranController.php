<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\KategoriTransaksi;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\MetodeBayar;
use App\Models\Pemasukan;
use App\Models\PengaturanMedia;
use App\Models\PengaturanProfil;
use App\Models\Perusahaan;
use App\Models\Piutang;
use App\Models\PemasukanRetensi;
use App\Models\ProgresListPenjualan;
use App\Models\Retensi;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF;
use Yajra\DataTables\Facades\DataTables;

Carbon::setLocale('id');
class PembayaranController extends Controller
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
            $data = Customer::with(['piutangs.kategori', 'pemasukans.kategori',  'progres', 'marketing', 'lokasi', 'kavling'])
                ->with(['pemasukans' => function ($q) {
                    $q->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%');
                }])
                ->whereHas('lokasi')
                ->where('stt_arsip', 0)
                ->orderBy('id', 'desc');

            if ($request->status) {
                if ($request->status == 'Lunas') {
                    $data->whereHas('piutangs', function ($q) {
                        $q->select('id_customer') // jangan pakai *
                            ->groupBy('id_customer')
                            ->havingRaw('SUM(sisa_bayar) = 0');
                    });
                } elseif ($request->status == 'Terhutang') {
                    $data->whereHas('piutangs', function ($q) {
                        $q->select('id_customer')
                            ->groupBy('id_customer')
                            ->havingRaw('SUM(sisa_bayar) > 0');
                    });
                }
            }

            if ($request->progres) {
                $data->where('id_status_progres', $request->progres);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    $badge = '';
                    if ($row->jenis_pembelian == 'Pembelian Cash') {
                        $badge = '<span class="badge badge-success">' . $row->jenis_pembelian . '</span>';
                    } elseif ($row->jenis_pembelian == 'Cash Bertahap') {
                        $badge = '<span class="badge badge-primary">' . $row->jenis_pembelian . '</span>';
                    } elseif ($row->jenis_pembelian == 'KPR') {
                        $badge = '<span class="badge badge-danger">' . $row->jenis_pembelian . '</span>';
                    }
                    return '<div><strong>' . e($row->nama_lengkap) . '</strong><br>' . e($row->no_telp) . '<br>' . $badge . '</div>';
                })
                ->filterColumn('customer', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('nama_lengkap', 'like', "%{$keyword}%")
                            ->orWhere('no_telp', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('lokasi_unit', function ($row) {
                    $lokasi  = $row->lokasi->nama_kavling ?? '-';
                    $kavling = $row->kavling->kode_kavling ?? '-';
                    return '<strong>' . $lokasi . '</strong><br>' . $kavling;
                })
                ->addColumn('status', function ($row) {
                    $status = $row->progres ? $row->progres->status_progres : '';

                    if ($row->id_marketing == 0) {
                        $marketing = '<span class="badge badge-info">Non Marketing</span>';
                    } else {
                        $marketing = $row->marketing
                            ? '<span class="badge badge-info">' . $row->marketing->nama_marketing . '</span>'
                            : '';
                    }

                    return '<div>' . e($status) . '<br>' . $marketing . '</div>';
                })
                ->addColumn('jumlah_tagihan', function ($row) {
                    $totalTagihan = $row->piutangs->sum('nominal');
                    $totalBayar   = $row->pemasukans->sum('nominal');
                    $sisa         = max($totalTagihan - $totalBayar, 0);

                    if ($sisa == 0 && $totalTagihan > 0) {
                        return '<img src="' . asset('assets/img/lunas.jpg') . '" width="100px">';
                    }

                    $html  = '<span class="badge badge-warning">Tagihan : Rp. ' . number_format($totalTagihan, 0, ',', '.') . '</span><br>';
                    $html .= '<span class="badge badge-success">Sudah Bayar : Rp. ' . number_format($totalBayar, 0, ',', '.') . '</span><br>';
                    $html .= '<span class="badge badge-danger">Sisa Bayar : Rp. ' . number_format($sisa, 0, ',', '.') . '</span>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $editUrl  = route('pembayaran.show', $row->id);
                    $btn      = '<div class="d-flex justify-content-center">';
                    $btn     .= '<a class="btn btn-success btn-sm" href="' . e($editUrl) . '">Detail</a>';
                    $btn     .= '</div>';
                    return $btn;
                })
                ->rawColumns(['customer', 'lokasi_unit', 'status', 'jumlah_tagihan', 'action'])
                ->make(true);
        }

        $progreslists = ProgresListPenjualan::all(['id', 'status_progres']);

        return view('admin.pembayaran.index', compact('permissions', 'progreslists'));
    }

    public function cetakRekap($id)
    {
        Carbon::setLocale('id');

        $customer = Customer::with([
            'piutangs',
            'pemasukans' => function ($q) {
                $q->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%');
            }
        ])->findOrFail($id);

        $lokasi          = LokasiKavling::with('perusahaan')->find($customer->id_lokasi);
        $kavling         = KavlingPeta::with('perusahaan')->find($customer->id_kavling);
        $perusahaan      = $kavling->perusahaan;
        if (! $perusahaan && $lokasi) {
            $perusahaanId = $lokasi->perusahaan->first()->id_perusahaan ?? null;
            $perusahaan   = $perusahaanId ? Perusahaan::find($perusahaanId) : null;
        }

        $namaPerusahaan   = $lokasi->nama_kavling ?? 'Nama Perusahaan Belum diisi';
        $alamatPerusahaan = $perusahaan->alamat_perusahaan ?? 'Alamat Belum diisi';
        $telpPerusahaan   = $perusahaan->telp_perusahaan ?? '-';
        $profilPerusahaan = PengaturanProfil::first();
        $namaProfil       = $profilPerusahaan->nama_perusahaan ?? 'PT. ALAM INDAH SELALU';
        $telpProfil       = $profilPerusahaan->telp ?? '0778-4173387';
        $kopPath         = public_path('assets/img/kop-kwitansi.jpg');
        $pengaturanMedia = PengaturanMedia::where('jenis_data', 'Logo Rekap')->first();
        $logoPath        = null;
        if ($pengaturanMedia && $pengaturanMedia->nama_file) {
            $logoPath = public_path('config_media/' . $pengaturanMedia->nama_file);
        }

        $pdf = new TCPDF('P', 'mm', 'A4');
        $pdf->SetTitle('Rekap Pembayaran' . ' - ' . $customer->nama_lengkap);
        $pdf->AddPage();

        if (file_exists($kopPath)) {
            $pdf->Image($kopPath, 5, 5, 200, 0, 'JPG', '', '', false, 100);

            $pdf->SetFont('helvetica', 'B', 18);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(60, 16);
            $pdf->Cell(140, 5, strtoupper($namaPerusahaan), 0, 1, 'C');

            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetX(60);
            $pdf->Cell(140, 4, $alamatPerusahaan, 0, 1, 'C');
            $pdf->SetX(60);
            $pdf->Cell(140, 4, 'Telp: ' . $telpPerusahaan, 0, 1, 'C');

            $lineY1 = 33.5;
            $pdf->SetLineWidth(0.3);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Line(9, $lineY1, 200, $lineY1);

            $pdf->SetY(48);
        } else {
            if ($logoPath && file_exists($logoPath)) {
                $pdf->Image($logoPath, 15, 15, 25);
            }

            $pdf->SetXY(57, 14);

            $pdf->SetFont('helvetica', 'B', 20);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 7, strtoupper($namaPerusahaan), 0, 1, 'L');

            $pdf->SetFont('Times', '', 9);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 5, $alamatPerusahaan, 0, 1, 'C');
            $pdf->Cell(0, 5, 'Telp: ' . $telpPerusahaan, 0, 1, 'C');

            $pdf->SetXY(0, 32);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(0.7);
            $pdf->Line(10, 42, 200, 42);

            $pdf->SetLineWidth(0.3);
            $pdf->Line(10, 41, 200, 41);

            $pdf->Ln(10);
        }

        $pdf->SetFont('Times', 'B', 10);
        $pdf->SetTextColor(218, 0, 0);
        $pdf->Cell(190, 8, 'TABEL REKAP PEMBAYARAN', 0, 1, 'C');
        $pdf->Ln(3);

        $pdf->SetFont('Times', '', 9);
        $pdf->SetTextColor(0, 0, 0);

        $startY = $pdf->GetY();

        $pdf->SetXY(10, $startY);
        $pdf->Cell(20, 6, 'Nama', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->Cell(60, 6, strtoupper($customer->nama_lengkap), 0, 1);

        $pdf->SetX(10);
        $pdf->Cell(20, 6, 'No. KTP', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->Cell(60, 6, $customer->nik ?? '-', 0, 1);

        $alamatY = $pdf->GetY();
        $pdf->SetXY(10, $alamatY);
        $pdf->Cell(20, 6, 'Alamat', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->MultiCell(55, 6, $customer->alamat_domisili ?? '-', 0, 'L', false, 1);

        $leftSectionBottomY = $pdf->GetY();

        $pdf->SetXY(90, $startY);
        $pdf->Cell(25, 6, 'Blok/Kav', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->Cell(40, 6, $kavling->kode_kavling ?? '-', 0, 1);

        $pdf->SetX(90);
        $pdf->Cell(25, 6, 'Luas Tanah', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->Cell(40, 6, ($kavling->luas_tanah ?? '-') . ' m²', 0, 1);
        $pdf->SetX(90);
        $pdf->Cell(25, 6, 'Luas Bangunan', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->Cell(40, 6, ($kavling->luas_bangunan ?? '-') . ' m²', 0, 1);

        $totalTagihan = $customer->piutangs->sum('nominal');
        $jumlahBayar  = $customer->pemasukans->sum('nominal');
        $sisaRingkas  = max($totalTagihan - ($customer->estimasi_plafon ?? 0) - ($customer->sbum ?? 0) - $jumlahBayar, 0);

        $pdf->SetX(90);
        $pdf->Cell(25, 6, 'Harga Rumah', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->Cell(42, 6, 'Rp. ' . number_format($kavling->hrg_jual ?? 0, 0, ',', '.'), 0, 1);

        $pdf->SetX(90);
        $pdf->Cell(25, 6, 'Jenis Pembelian', 0, 0);
        $pdf->Cell(3, 6, ':', 0, 0);
        $pdf->Cell(42, 6, ': ' . ($customer->jenis_pembelian ?? '-'), 0, 1);

        $detailSectionBottomY = max($leftSectionBottomY, $pdf->GetY());
        $pdf->SetY($detailSectionBottomY);

        $labelX = 138;
        $labelW = 28;
        $valueW = 28;

        $pdf->SetXY($labelX +5, $startY);
        $pdf->Cell($labelW, 6, 'Total Tagihan', 0, 0);
        $pdf->Cell(3, 6, ': Rp.', 0, 0);
        $pdf->Cell($valueW, 6, number_format($totalTagihan, 0, ',', '.'), 0, 1, 'R');

        $pdf->SetX($labelX+5);
        $pdf->Cell($labelW, 6, 'Sudah Dibayar', 0, 0);
        $pdf->Cell(3, 6, ': Rp.', 0, 0);
        $pdf->Cell($valueW, 6, number_format($jumlahBayar, 0, ',', '.'), 0, 1, 'R');

        $pdf->SetX($labelX+5);
        $pdf->Cell($labelW, 6, 'Estimasi Plafon', 0, 0);
        $pdf->Cell(3, 6, ': Rp.', 0, 0);
        $pdf->Cell($valueW, 6, number_format($customer->estimasi_plafon ?? 0, 0, ',', '.'), 0, 1, 'R');

        $pdf->SetX($labelX+5);
        $pdf->Cell($labelW, 6, 'SBUM', 0, 0);
        $pdf->Cell(3, 6, ': Rp.', 0, 0);
        $pdf->Cell($valueW, 6, number_format($customer->sbum ?? 0, 0, ',', '.'), 0, 1, 'R');

        $pdf->SetX($labelX+5);
        $pdf->Cell($labelW, 6, 'Sisa Bayar', 0, 0);
        $pdf->Cell(3, 6, ': Rp.', 0, 0);
        $pdf->Cell($valueW, 6, number_format($sisaRingkas, 0, ',', '.'), 0, 1, 'R');

        $colNo  = 8;
        $colTgl = 30;
        $colKet = 75;
        $colBay = 33;
        $colSis = 34;
        $tableX = 10;

        $pdf->Ln(10);
        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetFillColor(211, 236, 230);
        $pdf->SetXY($tableX, $pdf->GetY());
        $pdf->Cell($colNo, 7, 'No', 1, 0, 'C', true);
        $pdf->Cell($colTgl, 7, 'Tanggal', 1, 0, 'C', true);
        $pdf->Cell($colKet, 7, 'Keterangan', 1, 0, 'L', true);
        $pdf->Cell($colBay, 7, 'Pembayaran', 1, 0, 'C', true);
        $pdf->Cell($colSis, 7, 'Sisa Pembayaran', 1, 1, 'C', true);

        $pdf->SetFont('Times', '', 9);
        $no   = 1;
        $sisa = $totalTagihan - ($customer->estimasi_plafon ?? 0) - ($customer->sbum ?? 0);

        foreach ($customer->pemasukans->sortBy('id') as $byr) {

            $sisa -= $byr->nominal;

            $keterangan = explode('#', $byr->keterangan)[0] ?? '';
            $keteranganText = ($sisa <= 0) ? 'LUNAS' : $keterangan;

            $fill = ($no % 2 == 0)
                ? [255, 243, 243]
                : [255, 255, 255];

            $pdf->SetFillColor(...$fill);

            $startY = $pdf->GetY();
            $startX = $tableX;

            $xKet = $startX + $colNo + $colTgl;
            $pdf->SetXY($xKet, $startY);
            $pdf->MultiCell($colKet, 7, $keteranganText, 1, 'L', true);
            $rowH = max($pdf->GetY() - $startY, 7);

            $pdf->SetXY($startX, $startY);
            $pdf->Cell($colNo, $rowH, $no++, 1, 0, 'C', true);
            $pdf->Cell($colTgl, $rowH, Carbon::parse($byr->tanggal)->translatedFormat('j F Y'), 1, 0, 'C', true);

            $pdf->SetXY($startX + $colNo + $colTgl + $colKet, $startY);
            $pdf->Cell($colBay, $rowH, 'Rp. ' . number_format($byr->nominal, 0, ',', '.'), 1, 0, 'R', true);
            $pdf->Cell($colSis, $rowH, 'Rp. ' . number_format(max($sisa, 0), 0, ',', '.'), 1, 1, 'R', true);

            $pdf->SetY($startY + $rowH);
        }

        $pdf->Ln(10);
        $pdf->SetFont('', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $catatan = "Catatan:\n"
            . "- Bukti pembayaran dinyatakan sah apabila disertai kwitansi dari tangan pemilik kavling.\n"
            . "- Apabila ada yang mengaku-ngaku petugas kami \"" . $namaProfil . "\" meminta/menagih pembayaran angsuran, harap waspada. HATI-HATI PENIPUAN.\n"
            . "- Konsumen dapat menanyakan atau menghubungi informasi resmi \"" . $namaProfil . "\" di nomor " . $telpProfil . ".";
        $pdf->MultiCell(0, 0, $catatan, 0, 'L');

        $pdf->Ln(15);
        $tanggal = Carbon::now()->translatedFormat('j F Y');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(60, 6, 'Mengetahui,', 0, 0, 'C');
        $pdf->Cell(70, 6, '', 0, 0);
        $pdf->Cell(60, 6, 'Jambi, ' . $tanggal, 0, 1, 'C');
        $pdf->Cell(60, 6, 'Direktur', 0, 0, 'C');
        $pdf->Cell(70, 6, '', 0, 0);
        $pdf->Cell(60, 6, 'Admin', 0, 1, 'C');
        $pdf->Ln(20);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(60, 6, $perusahaan->nama_mengetahui ?? '....................', 'B', 0, 'C');
        $pdf->Cell(70, 6, '', 0, 0);
        $pdf->Cell(60, 6, $perusahaan->nama_penandatangan ?? 'ADMIN', 'B', 1, 'C');

        $pdf->Output();
        exit;
    }

    // public function cetak($id)
    // {
    //     $pembayaran = Pemasukan::with(['customer', 'metode', 'kategori'])->where('id', $id)
    //         ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
    //         ->firstOrFail();
    //     $nasabah = $pembayaran->customer;

    //     $alamatNasabah = $nasabah->alamat ?? $nasabah->alamat_ktp ?? $nasabah->alamat_domisili ?? '-';

    //     $lokasi = LokasiKavling::where('id', $nasabah->id_lokasi)->first();
    //     $namaKavling = $lokasi->nama_kavling ?? '-';

    //     $kavling = KavlingPeta::with('perusahaan')->where('id', $nasabah->id_kavling)->first();
    //     $dataPerusahaan = $kavling->perusahaan;

    //     $blokNomor = '-';
    //     if ($lokasi) {
    //         if ($lokasi->is_cluster) {
    //             $blokNomor = ($kavling->cluster ?? '-') . '-' . ($kavling->no ?? '-');
    //         } else {
    //             $blokNomor = $kavling->kode_kavling ?? '-';
    //         }
    //     }

    //     $templatePath = public_path('templates/kwitansi.pdf');
    //     $checkIcon = public_path('check-solid.png');
    //     $checkSize = 5;

    //     $pdf = new Fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
    //     $pdf->SetTitle('Kwitansi - ' . ($pembayaran->no_kwitansi ?? '-'));
    //     $pdf->SetAuthor('GIA Group');
    //     $pdf->SetPrintHeader(false);
    //     $pdf->SetPrintFooter(false);
    //     $pdf->SetMargins(0, 0, 0);
    //     $pdf->SetAutoPageBreak(false, 0);

    //     $pdf->setSourceFile($templatePath);
    //     $tplId = $pdf->importPage(1);

    //     $pdf->AddPage();
    //     $pdf->useTemplate($tplId, 0, 0, 210, 297);

    //     $pdf->SetFont('Times', '', 14);
    //     $pdf->SetTextColor(0, 0, 0);

    //     $pt = 25.4 / 72;

    //     $pdf->SetXY(470 * $pt, 100 * $pt);
    //     $pdf->Cell(40, 6, $pembayaran->no_kwitansi ?? '-', 0, 0, 'L');

    //     // nominal Bayar
    //     $pdf->SetFont('Times', 'B', 13);
    //     $pdf->SetXY(80 * $pt, 133 * $pt);
    //     $pdf->Cell(80, 6, number_format($pembayaran->nominal, 0, ',', '.'), 0, 0, 'L');

    //     $pdf->SetFont('Times', 'I', 13);
    //     $pdf->SetXY(200 * $pt, 133 * $pt);
    //     $pdf->Cell(200, 6, $this->terbilang($pembayaran->nominal) . ' Rupiah', 0, 0, 'L');

    //     // Centang Pembayaran
    //     $namaKategori = strtoupper($pembayaran->kategori->kategori ?? '-');
    //     $isBookingFee = str_contains($namaKategori, 'BOOKING FEE');
    //     $isUangMuka = str_contains($namaKategori, 'DP') || str_contains($namaKategori, 'UANG MUKA');
    //     $isSertifikat = str_contains($namaKategori, 'SERTIFIKAT');

    //     if ($isBookingFee) {
    //         if (file_exists($checkIcon)) {
    //             $pdf->Image($checkIcon, 42 * $pt, 174 * $pt, $checkSize);
    //         }
    //         $pdf->SetFont('Times', 'I', 12);
    //         $keteranganKategori = $pembayaran->keterangan_kategori ?? '';
    //         $labelLain = $pembayaran->kategori->kategori ?? '-';
    //         if ($keteranganKategori !== '') {
    //             $labelLain = $keteranganKategori;
    //         }
    //         $pdf->SetXY(130 * $pt, 174 * $pt);
    //         $pdf->Cell(100, 5, $labelLain, 0, 0, 'L');
    //     } elseif ($isUangMuka) {
    //         if (file_exists($checkIcon)) {
    //             $pdf->Image($checkIcon, 42 * $pt, 192 * $pt, $checkSize);
    //         }
    //         $pdf->SetFont('Times', 'I', 12);
    //         $keteranganKategori = $pembayaran->keterangan_kategori ?? '';
    //         $labelLain = $pembayaran->kategori->kategori ?? '-';
    //         if ($keteranganKategori !== '') {
    //             $labelLain = $keteranganKategori;
    //         }
    //         $pdf->SetXY(130 * $pt, 192 * $pt);
    //         $pdf->Cell(100, 5, $labelLain, 0, 0, 'L');
    //     } elseif ($isSertifikat) {
    //         if (file_exists($checkIcon)) {
    //             $pdf->Image($checkIcon, 301 * $pt, 174 * $pt, $checkSize);
    //         }
    //         $pdf->SetFont('Times', 'I', 12);
    //         $keteranganKategori = $pembayaran->keterangan_kategori ?? '';
    //         $labelLain = $pembayaran->kategori->kategori ?? '-';
    //         if ($keteranganKategori !== '') {
    //             $labelLain = $keteranganKategori;
    //         }
    //         $pdf->SetXY(410 * $pt, 174 * $pt);
    //         $pdf->Cell(100, 5, $labelLain, 0, 0, 'L');
    //     } else {
    //         if (file_exists($checkIcon)) {
    //             $pdf->Image($checkIcon, 301 * $pt, 192 * $pt, $checkSize);
    //         }
    //         $pdf->SetFont('Times', 'I', 12);
    //         $keteranganKategori = $pembayaran->keterangan_kategori ?? '';
    //         $labelLain = $pembayaran->kategori->kategori ?? '-';
    //         if ($keteranganKategori !== '') {
    //             $labelLain = $keteranganKategori;
    //         }
    //         $pdf->SetXY(410 * $pt, 192 * $pt);
    //         $pdf->Cell(100, 5, $labelLain, 0, 0, 'L');
    //     }







    //     // Nama Kavling
    //     $pdf->SetXY(140 * $pt, 214 * $pt);
    //     $pdf->Cell(80, 6, $namaKavling, 0, 0, 'C');

    //     $pdf->SetXY(150 * $pt, 232 * $pt);
    //     $pdf->Cell(150, 6, $nasabah->nama_lengkap ?? '-', 0, 0, 'L');

    //     $pdf->SetXY(150 * $pt, 251 * $pt);
    //     $pdf->Cell(200, 6, $alamatNasabah, 0, 0, 'L');




    //     $totalHarga = Piutang::where('id_customer', $nasabah->id)->sum('nominal');
    //     $pdf->SetXY(150 * $pt, 291 * $pt);
    //     $pdf->Cell(100, 6, number_format($totalHarga, 0, ',', '.'), 0, 0, 'L');

    //     $pdf->SetXY(150 * $pt, 310 * $pt);
    //     $pdf->Cell(100, 6, $kavling->tipe_bangunan ?? '-', 0, 0, 'L');

    //     $pdf->SetXY(150 * $pt, 329 * $pt);
    //     $pdf->Cell(100, 6, $blokNomor, 0, 0, 'L');


    //     $tanggalFormatted = Carbon::parse($pembayaran->tanggal)->translatedFormat('d F Y');
    //     // $pdf->SetFont('Times', '', 9);
    //     $pdf->SetXY(462 * $pt, 353 * $pt);
    //     $pdf->Cell(60, 6, $tanggalFormatted, 0, 0, 'L');

    //     $pdf->Output('Kwitansi_' . ($pembayaran->no_kwitansi ?? 'draft') . '.pdf', 'I');
    //     exit;
    // }




    public function cetak($id)
    {
        $pembayaran = Pemasukan::with(['customer', 'metode'])
            ->where('id', $id)
            ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
            ->firstOrFail();

        $nasabah    = $pembayaran->customer;
        $lokasi     = LokasiKavling::with('perusahaan')->find($nasabah->id_lokasi);
        $kavling    = KavlingPeta::with('perusahaan')->find($nasabah->id_kavling);
        $perusahaan = $kavling->perusahaan;

        if (! $perusahaan && $lokasi) {
            $perusahaanId = $lokasi->perusahaan->first()->id_perusahaan ?? null;
            $perusahaan   = $perusahaanId ? Perusahaan::find($perusahaanId) : null;
        }

        $blokNomor = $lokasi && $lokasi->is_cluster
            ? (($kavling->cluster ?? '-') . '.' . ($kavling->no ?? '-'))
            : str_replace('-', '.', $kavling->kode_kavling ?? '-');

        $namaPerusahaan   = $lokasi->nama_kavling ?? 'Nama Perusahaan Belum diisi';
        $alamatPerusahaan = $perusahaan->alamat_perusahaan ?? 'Alamat Belum diisi';

        $pageFormat = [140, 210];
        $pdf = new TCPDF('L', 'mm', $pageFormat, true, 'UTF-8', false);
        $pdf->SetTitle('Kwitansi - ' . ($pembayaran->no_kwitansi ?? '-'));
        $pdf->SetAuthor('Dealaska');
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('L', $pageFormat);

        $marginL  = 15;
        $marginR  = 15;
        $pageW    = 210;
        $contentW = $pageW - $marginL - $marginR;

        $kopPath  = public_path('assets/img/kop-kwitansi.jpg');
        $logoPath = public_path('templates/logo_rhabayu.jpg');


            $pdf->Image($kopPath, 5, 5, 200, 0, 'JPG', '', '', false, 100);

            $pdf->SetFont('helvetica', 'B', 18);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(60, 16);
            $pdf->Cell(140, 5, strtoupper($namaPerusahaan), 0, 1, 'C');

            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetX(60);
            $pdf->Cell(140, 4, $alamatPerusahaan, 0, 1, 'C');
            $pdf->SetX(60);
            $pdf->Cell(140, 4, 'Telp: ' . ($perusahaan->telp_perusahaan ?? '-'), 0, 1, 'C');

            // if (file_exists($logoPath)) {
            //     $pdf->Image($logoPath, 140, 13, 55, 0, '', '', '', false, 150);
            // }


        $lineY1 = 33.5;
        $pdf->SetLineWidth(0.3);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Line(9, $lineY1, 200, $lineY1);

        $pdf->ln(2);
        $pdf->SetFont('helvetica', 'BU', 14);
        $pdf->SetX(15);
        $pdf->Cell(180, 10, 'KWITANSI', 0, 0, 'C');

        $pdf->ln(3);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetX(15);
        $pdf->Cell(30, 5, 'No. : ' . ($pembayaran->no_kwitansi ?? '-'), 'B', 1, 'L');
        $pdf->ln(5);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetX(15);
        $pdf->Cell(48, 6, 'Telah Diterima Dari', 0, 0, 'L');
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(127, 6, strtoupper($nasabah->nama_lengkap) ?? '-', 'B', 1);

        $pdf->ln(3);
        $pdf->SetX(15);

        $pdf->Cell(48, 8, 'Uang Sejumlah', 0, 0);
        $pdf->Cell(5, 8, ':', 0, 0);
        $pdf->SetFillColor(219, 153, 47);
        $pdf->Cell(127, 8, '#'.strtoupper($this->terbilang($pembayaran->nominal)) . ' RUPIAH#', 0, 1, 'L', true);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->ln(3);
        $pdf->SetX(15);
        $pdf->Cell(48, 6, 'Untuk Pembayaran', 0, 0);
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(127, 6, $pembayaran->keterangan ?? '-', 'B', 1, 'L');

        $pdf->ln(2);
        $pdf->SetX(15);
        $pdf->Cell(180, 6, '', 'B', 1, 'L');
        // ----------

        $pdf->ln(3);
        $pdf->SetX(15);
        $pdf->Cell(180, 5, 'Cara Pembayaran :', 0, 0, 'L');
        $pdf->SetX(55);
        $pdf->Cell(50, 5, strtoupper($pembayaran->metode->jenis_bayar ?? 'CASH'), 0, 0, 'L');
        $tanggal = \Carbon\Carbon::parse($pembayaran->tanggal)->locale('id')->isoFormat('D MMMM YYYY');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetX(145);
        $pdf->Cell(40, 6, 'Jambi, '.$tanggal, 'B', 1,'C');


        $pdf->ln(10);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetX(15);
        $pdf->Cell(15, 10, 'Rp.', 'TB', 0, 'C', false);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(219, 153, 47);
        $pdf->SetX(30);
        $pdf->Cell(35, 10, ' ' . number_format($pembayaran->nominal, 0, ',', '.') . ',-', 'LTBR', 0, 'L', true);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->ln(7);
        $pdf->SetX(92);
        $pdf->Cell(35, 6, $perusahaan->nama_mengetahui, 'B', 0, 'C');
        $pdf->Cell(20, 6, '', 0, 0, 'C');
        $pdf->Cell(35, 6, $perusahaan->nama_penandatangan, 'B', 1, 'C');

        $pdf->SetX(92);
        $pdf->Cell(35, 6, 'Direktur', 0, 0, 'C');
        $pdf->Cell(20, 6, '', 0, 0, 'C');
        $pdf->Cell(35, 6, 'Admin', 0, 1, 'C');


        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX(15);
        $pdf->Cell(45, 4, 'Catatan : ', 0, 1, 'l');
        $pdf->SetX(15);
        $pdf->Cell(45, 4, '1. Setelah melakukan pembayaran, segera konfirmasi ke WA 082173603773 : ', 0, 1, 'l');
        $pdf->SetX(15);
        $pdf->Cell(45, 4, '2. Butuh informasi bubungi 082173603773 : ', 0, 1, 'l');

        $filename = 'Kwitansi-' . ($pembayaran->no_kwitansi ?? 'draft') . '.pdf';
        $pdf->Output($filename, 'I');
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

    public function show($id)
    {
        $customer = Customer::with([
            'piutangs',
            'lokasi',
            'kavlingPeta',
            'pemasukans' => function ($q) {
                $q->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%');
            }
        ])->findOrFail($id);

        $metodeBayar                = MetodeBayar::all();
        $bankList                   = Bank::all();
        $kategoriTransaksiPemasukan = KategoriTransaksi::where('jenis_kategori', 'PEMASUKAN')
            ->get();

        $kategoriTransaksiTagihan = KategoriTransaksi::orderBy('kategori')->get();

        $piutang = Piutang::where('id_customer', $id)
            ->where('id_kategori_transaksi', '!=', 0)
            ->get();

        $piutang = Piutang::with('kategori')
            ->where('id_customer', $id)
            ->where('id_kategori_transaksi', '!=', 0)
            ->get();

        $retensis = Retensi::orderBy('id')->get();

        $defaultNoKwitansi = '';
        try {
            $defaultNoKwitansi = $this->generator->generateNomorDokumen(
                $customer->lokasi,
                'no_kwitansi',
                Pemasukan::class
            );
        } catch (\Exception $e) {
            $defaultNoKwitansi = '';
        }

        return view('admin.pembayaran.detail', compact(
            'customer',
            'metodeBayar',
            'bankList',
            'kategoriTransaksiPemasukan',
            'kategoriTransaksiTagihan',
            'piutang',
            'retensis',
            'defaultNoKwitansi'
        ));
    }

    private function getTotalTagihanCustomer($customerId)
    {
        return Piutang::where('id_customer', $customerId)->sum('nominal');
    }

    private function getJumlahBayarCustomer($customerId)
    {
        return Pemasukan::where('id_customer', $customerId)
            ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
            ->where('id_kategori_transaksi', '!=', 4)
            ->sum('nominal');
    }

    private function getSisaBayarCustomer($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $totalTagihan = $this->getTotalTagihanCustomer($customerId);
        $jumlahBayar = $this->getJumlahBayarCustomer($customerId);

        return max($totalTagihan - ($customer->estimasi_plafon ?? 0) - ($customer->sbum ?? 0) - $jumlahBayar, 0);
    }

    public function detailTagihan(Request $request, $id)
    {
        if ($request->ajax()) {
            $tagihanList  = Piutang::where('id_customer', $id)->orderBy('id')->get();
            $totalTagihan = $tagihanList->sum('nominal');

            return DataTables::of($tagihanList)
                ->addIndexColumn()
                ->addColumn('jumlah_tagihan', function ($row) {
                    return '<div class="input-group input-group-sm" style="max-width:200px; margin-left:auto;">
                        <div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>
                        <input type="text" class="form-control format-number edit-nominal text-right" value="' . number_format($row->nominal, 0, ',', '.') . '" data-id="' . $row->id . '">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success btn-sm save-nominal" data-id="' . $row->id . '"><i class="fa fa-check"></i></button>
                        </div>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    if (str_contains($row->deskripsi, 'Harga Rumah')) {
                        return '';
                    }
                    $deleteUrl = route('pembayaran.delete-tagihan', $row->id);
                    return '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-tagihan btn btn-danger btn-xs">Hapus</button></form>';
                })
                ->rawColumns(['action', 'jumlah_tagihan'])
                ->with('total_tagihan', $totalTagihan)
                ->with('total_tagihan_formatted', number_format($totalTagihan, 0, ',', '.'))
                ->make(true);
        }
    }

    public function tambahTagihan(Request $request, $id)
    {
        $rules = [
            'id_kategori' => 'required',
            'deskripsi'   => 'required',
            'nominal'     => 'required',
        ];

        $messages = [
            'id_kategori.required' => 'Kategori Transaksi wajib dipilih.',
            'deskripsi.required'   => 'Deskripsi tagihan wajib diisi.',
            'nominal.required'     => 'Nominal wajib diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $cust    = Customer::find($id);
            $piutang = Piutang::create([
                'id_customer'           => $id,
                'id_bank'               => 0,
                'tanggal_piutang'       => Carbon::now(),
                'deskripsi'             => $request->deskripsi,
                'id_kategori_transaksi' => $request->id_kategori,
                'nominal'               => (int) str_replace(['.', ','], '', $request->nominal),
                'lampiran'              => '',
                'status'                => 1,
                'terbayar'              => 0,
                'sisa_bayar'            => (int) str_replace(['.', ','], '', $request->nominal),
            ]);

            $totalTagihan = Piutang::where('id_customer', $id)->sum('nominal');
            $sisaBayar    = Piutang::where('id_customer', $id)->sum('sisa_bayar');

            $this->logCreate('Detail Pembayaran', $piutang->id);

            DB::commit();
            return response()->json([
                'success'                 => true,
                'total_tagihan_formatted' => number_format($totalTagihan, 0, ',', '.'),
                'sisa_bayar_formatted'    => number_format($sisaBayar, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tagihan',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function UpdateHargaRumah($id)
    {
        DB::beginTransaction();
        try {
            $tagihan = Piutang::where('id_customer', $id)->first();
            if (! $tagihan) {
                throw new \Exception('Tagihan tidak ditemukan.');
            }

            $cust         = Customer::find($id);
            $kav          = KavlingPeta::find($cust->id_kavling);
            $nominal_baru = $kav->hrg_jual;

            $terbayar_lama = $tagihan->terbayar;

            $sisa_bayar_baru = $nominal_baru - $terbayar_lama;

            $tagihan->update([
                'nominal'    => $nominal_baru,
                'sisa_bayar' => $sisa_bayar_baru,
            ]);

            $totalTagihan = Piutang::where('id_customer', $id)->sum('nominal');
            $sisaBayar    = Piutang::where('id_customer', $id)->sum('sisa_bayar');

            DB::commit();
            return response()->json([
                'status'                  => 'success',
                'total_tagihan_formatted' => number_format($totalTagihan, 0, ',', '.'),
                'sisa_bayar_formatted'    => number_format($sisaBayar, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function updateEstimasiPlafon(Request $request, $id)
    {
        try {
            $request->validate([
                'estimasi_plafon' => 'required',
            ]);

            $customer = Customer::findOrFail($id);
            $estimasiPlafon = (int) str_replace(['.', ','], '', $request->estimasi_plafon);

            $customer->update(['estimasi_plafon' => $estimasiPlafon]);

            $totalTagihan = $this->getTotalTagihanCustomer($id);
            $jumlahBayar  = $this->getJumlahBayarCustomer($id);
            $sisaBayar    = max($totalTagihan - $estimasiPlafon - ($customer->sbum ?? 0) - $jumlahBayar, 0);

            return response()->json([
                'status'                    => 'success',
                'estimasi_plafon_formatted' => number_format($estimasiPlafon, 0, ',', '.'),
                'total_tagihan_formatted'   => number_format($totalTagihan, 0, ',', '.'),
                'jumlah_bayar_formatted'    => number_format($jumlahBayar, 0, ',', '.'),
                'sisa_bayar_formatted'      => number_format($sisaBayar, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate estimasi plafon.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateSbum(Request $request, $id)
    {
        try {
            $request->validate([
                'sbum' => 'required',
            ]);

            $customer = Customer::findOrFail($id);
            $sbum = (int) str_replace(['.', ','], '', $request->sbum);

            $customer->update(['sbum' => $sbum]);

            $totalTagihan = $this->getTotalTagihanCustomer($id);
            $jumlahBayar  = $this->getJumlahBayarCustomer($id);
            $sisaBayar    = max($totalTagihan - ($customer->estimasi_plafon ?? 0) - $sbum - $jumlahBayar, 0);

            return response()->json([
                'status'                  => 'success',
                'sbum_formatted'          => number_format($sbum, 0, ',', '.'),
                'total_tagihan_formatted' => number_format($totalTagihan, 0, ',', '.'),
                'jumlah_bayar_formatted'  => number_format($jumlahBayar, 0, ',', '.'),
                'sisa_bayar_formatted'    => number_format($sisaBayar, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate SBUM.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function DeleteTagihan($id)
    {
        $tagihan     = Piutang::findOrFail($id);
        $id_customer = $tagihan->id_customer;
        $tagihan->delete();

        Pemasukan::where('id_piutang', $id)
            ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
            ->delete();

        $totalTagihan = Piutang::where('id_customer', $id_customer)->sum('nominal');
        $jumlahBayar  = Pemasukan::where('id_customer', $id_customer)
            ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
            ->sum('nominal');
        $sisaBayar    = $totalTagihan - $jumlahBayar;

        return response()->json([
            'status'                  => 'success',
            'total_tagihan_formatted' => number_format($totalTagihan, 0, ',', '.'),
            'jumlah_bayar_formatted'  => number_format($jumlahBayar, 0, ',', '.'),
            'sisa_bayar_formatted'    => number_format($sisaBayar, 0, ',', '.'),
        ]);
    }

    public function updateTagihan(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $tagihan = Piutang::findOrFail($id);
            $id_customer = $tagihan->id_customer;
            $nominalBaru = (int) str_replace(['.', ','], '', $request->nominal);

            $tagihan->update([
                'nominal'    => $nominalBaru,
                'sisa_bayar' => $nominalBaru - $tagihan->terbayar,
            ]);

            $cust = Customer::find($id_customer);
            if ($cust && $cust->id_kavling) {
                $kavling = KavlingPeta::find($cust->id_kavling);
                if ($kavling) {
                    $deskripsi = $tagihan->deskripsi;
                    $updateKav = [];
                    if (str_contains($deskripsi, 'Harga Rumah')) {
                        $updateKav['hrg_jual'] = $nominalBaru;
                    }
                    if (str_contains($deskripsi, 'Biaya Surat')) {
                        $updateKav['biaya_surat'] = $nominalBaru;
                    }
                    if (str_contains($deskripsi, 'Peningkatan Mutu')) {
                        $updateKav['peningkatan_mutu'] = $nominalBaru;
                    }
                    if (!empty($updateKav)) {
                        $kavling->update($updateKav);
                    }
                }
            }

            $totalTagihan = Piutang::where('id_customer', $id_customer)->sum('nominal');
            $sisaBayar    = Piutang::where('id_customer', $id_customer)->sum('sisa_bayar');

            DB::commit();
            return response()->json([
                'success'                 => true,
                'total_tagihan_formatted' => number_format($totalTagihan, 0, ',', '.'),
                'sisa_bayar_formatted'    => number_format($sisaBayar, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate tagihan',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function detailPemasukan(Request $request, $id)
    {
        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = Pemasukan::with('kategori')
                ->where('id_customer', $id)
                ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
                ->get();

            foreach ($data as $item) {
                $deleteUrl              = route('pembayaran.delete-pemasukan', $item->id);
                $editUrl                = route('pembayaran.edit-pemasukan', $item->id);
                $item->tanggal          = '<div>' . Carbon::parse($item->tanggal)->translatedFormat('d F Y') . '</div>'
                    . '<div class="text-muted" style="font-size:11px;">' . ($item->no_kwitansi ?? '-') . '</div>';
                $item->kategori         = $item->kategori->kategori ?? '-';
                $item->jumlah_formatted = '
                    <div class="d-flex justify-content-between">
                        <span>Rp.</span>
                        <span>' . number_format($item->nominal, 0, ',', '.') . '</span>
                    </div>';

                $action = '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="delete-pemasukan btn btn-danger btn-xs">Hapus</button></form>';

                $action = '<button type="button" class="btn btn-xs btn-info edit-pemasukan-button" data-url="' . e($editUrl) . '">Edit</button> '
                    . $action;

                $action = '
                <a class="btn btn-xs btn-primary" href="' . route('pembayaran.cetak', $item->id) . '" target="_blank">Cetak</a>
            ' . $action;

                $item->action = $action;
            }

            $total = $data->sum('nominal');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', fn($item) => $item->tanggal)
                ->addColumn('keterangan', fn($item) => $item->keterangan ?? '-')
                ->addColumn('kategori', fn($item) => $item->kategori)
                ->addColumn('jumlah', fn($item) => $item->jumlah_formatted)
                ->addColumn('action', fn($item) => $item->action)
                ->with('total_pemasukan_formatted', number_format($total, 0, ',', '.'))
                ->rawColumns(['action', 'jumlah', 'tanggal'])
                ->make(true);
        }
    }

    public function tambahPemasukan(Request $request, $id)
    {
        $rules = [
            'tanggal_pembayaran'    => 'required|date',
            'id_kategori_transaksi' => 'required',
            'id_bank'               => 'required',
            'id_metode_bayar'       => 'required',
            'id_tagihan'            => 'required_if:id_kategori_transaksi,17',
            'nominal_bayar'         => 'required',
            'keterangan_pembayaran' => 'required',
            'file'                  => 'required_if:id_metode_bayar,2|file|mimes:jpeg,png,jpg,webp,pdf|max:2048',
        ];

        $messages = [
            'tanggal_pembayaran.required'    => 'Tanggal Pembayaran wajib diisi.',
            'id_kategori_transaksi.required' => 'Kategori Transaksi wajib diisi.',
            'id_bank.required'               => 'Bank wajib dipilih.',
            'id_metode_bayar.required'       => 'Metode Pembayaran wajib dipilih.',
            'id_tagihan.required_if'         => 'Tagihan wajib dipilih.',
            'nominal_bayar.required'         => 'Nominal wajib diisi.',
            'keterangan_pembayaran.required' => 'Keterangan wajib diisi.',
            'file.required_if'               => 'Lampiran wajib diunggah.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file')) {
                $file     = $request->file('file');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/keuangan/pemasukan/'), $filename);
            }

            $cust = Customer::with('lokasi')->lockForUpdate()->findOrFail($id);

            $no_kwitansi = $request->no_kwitansi ?? '';

            if (empty($no_kwitansi) && $request->id_kategori_transaksi != 4 && $request->id_kategori_transaksi != 21) {
                $no_kwitansi = $this->generator->generateNomorDokumen(
                    $cust->lokasi,
                    'no_kwitansi',
                    Pemasukan::class
                );
            }

            $pemasukan = Pemasukan::create([
                'tanggal'               => $request->tanggal_pembayaran,
                'id_customer'           => $id,
                'id_bank'               => $request->id_bank,
                'id_piutang'            => $request->id_tagihan ?? 0,
                'id_kategori_transaksi' => $request->id_kategori_transaksi,
                'no_kwitansi'           => $no_kwitansi,
                'nominal'               => str_replace('.', '', $request->nominal_bayar),
                'keterangan'            => $request->keterangan_pembayaran,
                'keterangan_kategori'   => $request->keterangan_kategori ?? '',
                'id_metode_bayar'       => $request->id_metode_bayar,
                'lampiran'              => $filename ?? '',
            ]);

            $this->logCreate('Detail Pembayaran', $pemasukan->id);

            if ($request->id_kategori_transaksi == 17) {
                $piutang = Piutang::find($request->id_tagihan);
                if ($piutang) {
                    $piutang->update([
                        'terbayar'   => $piutang->terbayar + str_replace('.', '', $request->nominal_bayar),
                        'sisa_bayar' => $piutang->sisa_bayar - str_replace('.', '', $request->nominal_bayar),
                    ]);
                }
            } else {
                $sisaBayar = str_replace('.', '', $request->nominal_bayar);

                $piutangs = Piutang::where('id_customer', $id)
                    ->where('status', 1)
                    ->orderBy('id')
                    ->get();

                foreach ($piutangs as $piutang) {
                    if ($sisaBayar <= 0) {
                        break;
                    }

                    if ($sisaBayar >= $piutang->sisa_bayar) {
                        $sisaBayar -= $piutang->sisa_bayar;

                        $piutang->update([
                            'terbayar'      => $piutang->terbayar + $piutang->sisa_bayar,
                            'sisa_bayar'    => 0,
                            'status'        => 2,
                            'tgl_pelunasan' => $request->tanggal_pembayaran,
                        ]);
                    } else {
                        $piutang->update([
                            'terbayar'   => $piutang->terbayar + $sisaBayar,
                            'sisa_bayar' => $piutang->sisa_bayar - $sisaBayar,
                        ]);

                        $sisaBayar = 0;
                    }
                }

                $masihAdaPiutang = Piutang::where('id_customer', $id)
                    ->where('status', 1)
                    ->exists();

                if (
                    ! $masihAdaPiutang &&
                    $request->id_kategori_transaksi == 5
                ) {
                    $cust->update(['id_status_progres' => 8]);
                }
            }

            $totalTagihan = $this->getTotalTagihanCustomer($id);
            $jumlahBayar  = $this->getJumlahBayarCustomer($id);
            $sisaBayar    = $this->getSisaBayarCustomer($id);

            DB::commit();

            return response()->json([
                'success'       => true,
                'jumlah_bayar'  => number_format($jumlahBayar, 0, ',', '.'),
                'total_tagihan' => number_format($totalTagihan, 0, ',', '.'),
                'sisa_bayar'    => number_format($sisaBayar, 0, ',', '.'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pemasukan',
            ], 500);
        }
    }

    public function tambahPencairanKpr(Request $request, $id)
    {
        $request->validate([
            'tanggal_pencairan' => 'required|date',
            'jumlah_plafon' => 'required',
            'jumlah_pencairan' => 'required',
        ], [
            'tanggal_pencairan.required' => 'Tanggal pencairan wajib diisi.',
            'jumlah_plafon.required' => 'Jumlah plafon wajib diisi.',
            'jumlah_pencairan.required' => 'Jumlah pencairan wajib diisi.',
        ]);

        $customer = Customer::findOrFail($id);
        $estimasiPlafon = (int) ($customer->estimasi_plafon ?? 0);

        if ($estimasiPlafon <= 0) {
            return response()->json([
                'message' => 'Estimasi plafon belum diisi. Silakan isi terlebih dahulu.',
            ], 422);
        }

        $jumlahPlafon = (int) str_replace('.', '', $request->jumlah_plafon);
        $jumlahPencairan = (int) str_replace('.', '', $request->jumlah_pencairan);
        $retensiInput = $request->input('retensi', []);
        $totalRetensi = 0;

        foreach ($retensiInput as $nominal) {
            $totalRetensi += (int) str_replace('.', '', $nominal ?? 0);
        }

        if ($jumlahPlafon !== ($jumlahPencairan + $totalRetensi)) {
            return response()->json([
                'message' => 'Jumlah plafon harus sama dengan jumlah pencairan ditambah total retensi.',
            ], 422);
        }

        if ($jumlahPlafon !== $estimasiPlafon) {
            return response()->json([
                'message' => 'Jumlah plafon harus sama dengan nilai estimasi plafon pada halaman pembayaran.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $pemasukan = Pemasukan::create([
                'tanggal' => $request->tanggal_pencairan,
                'id_customer' => $id,
                'id_bank' => 0,
                'id_piutang' => 0,
                'id_kategori_transaksi' => 4,
                'no_kwitansi' => '',
                'nominal' => $jumlahPencairan,
                'keterangan' => 'Pencairan KPR',
                'keterangan_kategori' => 'Pencairan KPR',
                'id_metode_bayar' => 2,
                'lampiran' => '',
            ]);

            foreach ($retensiInput as $retensiId => $nominal) {
                $nilaiRetensi = (int) str_replace('.', '', $nominal ?? 0);
                if ($nilaiRetensi <= 0) {
                    continue;
                }

                PemasukanRetensi::create([
                    'id_pemasukan' => $pemasukan->id,
                    'id_retensi' => $retensiId,
                    'nominal' => $nilaiRetensi,
                ]);
            }

            $this->logCreate('Pencairan KPR', $pemasukan->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'jumlah_bayar' => number_format($this->getJumlahBayarCustomer($id), 0, ',', '.'),
                'total_tagihan' => number_format($this->getTotalTagihanCustomer($id), 0, ',', '.'),
                'sisa_bayar' => number_format($this->getSisaBayarCustomer($id), 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pencairan KPR',
            ], 500);
        }
    }

    public function DeletePemasukan($id)
    {
        DB::beginTransaction();
        try {
            $pemasukan   = Pemasukan::findOrFail($id);
            $id_customer = $pemasukan->id_customer; // simpan dulu sebelum delete

            if (!empty($pemasukan->lampiran) && file_exists(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran))) {
                unlink(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran));
            }

            if ($pemasukan->id_kategori_transaksi != 4) {
                $nominal = $pemasukan->nominal;

                $piutangs = Piutang::where('id_customer', $id_customer)
                    ->where('terbayar', '>', 0)
                    ->orderByDesc('id')
                    ->lockForUpdate()
                    ->get();

                foreach ($piutangs as $piutang) {
                    if ($nominal <= 0) {
                        break;
                    }

                    if ($nominal >= $piutang->terbayar) {
                        $nominal -= $piutang->terbayar;

                        $piutang->update([
                            'sisa_bayar'    => $piutang->sisa_bayar + $piutang->terbayar,
                            'terbayar'      => 0,
                            'tgl_pelunasan' => null,
                        ]);
                    } else {
                        $piutang->update([
                            'sisa_bayar' => $piutang->sisa_bayar + $nominal,
                            'terbayar'   => $piutang->terbayar - $nominal,
                        ]);

                        $nominal = 0;
                    }

                    $piutang->update([
                        'status' => $piutang->terbayar == $piutang->nominal ? 2 : 1,
                    ]);
                }
            }

            PemasukanRetensi::where('id_pemasukan', $pemasukan->id)->delete();

            // delete dulu, baru hitung ulang dari tabel pemasukan
            $pemasukan->delete();
            $this->logDelete('Detail Pembayaran', $id);

            $totalTagihan = $this->getTotalTagihanCustomer($id_customer);
            $jumlahBayar  = $this->getJumlahBayarCustomer($id_customer);
            $sisaBayar    = $this->getSisaBayarCustomer($id_customer);

            DB::commit();

            return response()->json([
                'status'        => 'success',
                'jumlah_bayar'  => number_format($jumlahBayar, 0, ',', '.'),
                'total_tagihan' => number_format($totalTagihan, 0, ',', '.'),
                'sisa_bayar'    => number_format($sisaBayar, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus pemasukan',
            ], 500);
        }
    }

    public function editPemasukan($id)
    {
        $pemasukan = Pemasukan::with(['kategori', 'bank'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $pemasukan,
        ]);
    }

    public function updatePemasukan(Request $request, $id)
    {
        $request->validate([
            'tanggal_pembayaran'    => 'required|date',
            'id_kategori_transaksi' => 'required',
            'id_bank'               => 'required',
            'id_metode_bayar'       => 'required',
            'nominal_bayar'         => 'required',
            'keterangan_pembayaran' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $pemasukan = Pemasukan::findOrFail($id);

            if ($request->hasFile('file')) {
                if (!empty($pemasukan->lampiran) && file_exists(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran))) {
                    unlink(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran));
                }
                $file     = $request->file('file');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/keuangan/pemasukan/'), $filename);
            }

            $updateData = [
                'tanggal'               => $request->tanggal_pembayaran,
                'id_bank'               => $request->id_bank,
                'id_kategori_transaksi' => $request->id_kategori_transaksi,
                'nominal'               => str_replace('.', '', $request->nominal_bayar),
                'keterangan'            => $request->keterangan_pembayaran,
                'keterangan_kategori'   => $request->keterangan_kategori ?? '',
                'id_metode_bayar'       => $request->id_metode_bayar,
                'lampiran'              => $filename ?? $pemasukan->lampiran,
            ];

            if ($request->filled('no_kwitansi')) {
                $updateData['no_kwitansi'] = $request->no_kwitansi;
            }

            $pemasukan->update($updateData);

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengupdate pemasukan',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function print($id)
    {
        $pembayaran = Pemasukan::with(['customer', 'metode', 'kategori'])->where('id', $id)
            ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')->firstOrFail();
        $nasabah = $pembayaran->customer;
        $lokasi  = LokasiKavling::find($nasabah->id_lokasi ?? null)->first();
        $kavling = KavlingPeta::find($nasabah->id_kavling ?? null)->first();

        $noKwitansi  = $pembayaran->no_kwitansi ?? '-';
        $nama        = $nasabah->nama_lengkap ?? '-';
        $alamat      = $nasabah->alamat_ktp ?? $nasabah->alamat_domisili ?? '-';
        $jumlah      = $pembayaran->nominal ?? 0;
        $terbilang   = '#' . strtoupper($this->terbilang($jumlah)) . ' Rupiah#';
        $namaKavling = $lokasi->nama_kavling ?? '-';
        $tipe        = $kavling->tipe_bangunan ?? '-';
        $blokNomor   = '-';
        if ($lokasi) {
            if ($lokasi->is_cluster) {
                $blokNomor = ($kavling->cluster ?? '-') . '-' . ($kavling->no ?? '-');
            } else {
                $blokNomor = $kavling->kode_kavling ?? '-';
            }
        }
        $rumahId         = $kavling->id_rumah_sikumbang ?? '-';
        $hargaJual       = $kavling->hrg_jual ?? 0;
        $kotaTtd         = $lokasi->kota_penandatangan ?? '-';
        $tanggal         = $pembayaran->tanggal ? Carbon::parse($pembayaran->tanggal)->translatedFormat('d F Y') : '-';
        $jenisPembayaran = $pembayaran->kategori->kategori ?? 'Cicilan Pribadi';
        $keteranganKategori = $pembayaran->keterangan_kategori ?? '';
        if ($keteranganKategori !== '') {
            $jenisPembayaran .= ': ' . $keteranganKategori;
        }
        $metodeBayar     = $pembayaran->metode->jenis_bayar ?? 'CASH';

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Kwitansi');
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->SetMargins(20, 0, 0);    // Mengatur margin seperti dot matrix
        $pdf->SetAutoPageBreak(false); // Nonaktifkan auto page break
        $pdf->AddPage();
        $pdf->SetTextColor(0, 0, 0);

        // Tambahkan space atas seperti dot matrix
        $pdf->Ln(40);

        // Header - TANDA TERIMA dengan underline
        $pdf->SetFont('Helvetica', 'BU', 16);
        $pdf->Cell(170, 6, 'TANDA TERIMA', 0, 1, 'C');

        // Nomor kwitansi
        $pdf->SetFont('Helvetica', 'I', 12);
        $pdf->Cell(170, 6, $noKwitansi, 0, 1, 'C');

        // Detail penerima
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(100, 5, 'Sudah diterima dari : ', 0, 1, 'L');

        $pdf->Cell(20, 5, '', 0, 0, 'L');
        $pdf->Cell(25, 5, 'Nama', 0, 0, 'L');
        $pdf->Cell(60, 5, ' : ' . $nama, 0, 1, 'L');

        $pdf->Cell(20, 5, '', 0, 0, 'L');
        $pdf->Cell(25, 5, 'Alamat', 0, 0, 'L');
        $pdf->Cell(100, 5, ' : ' . $alamat, 0, 1, 'L');

        // Jumlah uang
        $pdf->Ln(3);
        $pdf->Cell(20, 5, 'Uang sejumlah Rp. ' . number_format($jumlah, 0, ',', '.') . ' (' . $terbilang . ')', 0, 1, 'L');
        $pdf->Cell(20, 5, 'Untuk pembayaran ' . $jenisPembayaran . ' atas pembelian rumah di ' . $namaKavling . ' : ', 0, 1, 'L');

        // Detail properti
        $pdf->Ln(1);
        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Perumahan', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $namaKavling, 0, 1, 'L');

        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Type', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $tipe, 0, 1, 'L');

        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $labelBlok = $lokasi->is_cluster ? 'Cluster / Nomor' : 'Blok / Nomor';
        $pdf->Cell(25, 4, $labelBlok, 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $blokNomor, 0, 1, 'L');

        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Rumah ID', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : ' . $rumahId, 0, 1, 'L');

        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(25, 4, 'Harga Jual', 0, 0, 'L');
        $pdf->Cell(60, 4, ' : Rp. ' . number_format($hargaJual, 0, ',', '.'), 0, 1, 'L');

        // Tanggal dan keterangan
        $pdf->SetFont('Helvetica', 'B', 8.5);
        $pdf->Cell(20, 4, '', 0, 1, 'L');
        $pdf->Cell(20, 4, '', 0, 0, 'L');
        $pdf->Cell(100, 4, '', 0, 0, 'L');
        $pdf->Cell(55, 4, $kotaTtd . ', ' . $tanggal, 0, 1, 'C');

        $pdf->Ln(2);

        // Footer dengan tanda tangan
        $pdf->SetFont('Helvetica', '', 8.5);
        $pdf->Cell(35, 5, 'Keterangan : ', 0, 0, 'L');
        $pdf->Cell(45, 5, 'Kasir', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Penyetor', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Customer Service', 0, 1, 'C');

        $pdf->Cell(35, 5, $metodeBayar, 0, 0, 'L');
        $pdf->Cell(100, 5, '', 0, 0, 'L');
        $pdf->Ln(20);

        // Garis untuk tanda tangan
        $pdf->SetLineWidth(0.2);
        $pdf->Line(60, 135, 95, 135);
        $pdf->Line(105, 135, 140, 135);
        $pdf->Line(150, 135, 185, 135);

        // Catatan kaki
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

    public function rekapPembayaran(Request $request)
    {
        $pembayaran = KategoriTransaksi::whereIn('id', [4, 21])->get();
        $lokasi     = LokasiKavling::orderBy('id', 'asc')->get();

        $metodeBayar = MetodeBayar::all();
        $bankList    = Bank::all();

        if ($request->ajax()) {
            $data = KavlingPeta::with(['customer', 'lokasi'])

                ->whereHas('lokasi', function ($q) use ($request) {
                    if ($request->lokasi_id) {
                        $q->where('id', $request->lokasi_id);
                    }
                })

                ->when($request->blok, function ($q) use ($request) {
                    $q->where('kode_kavling', 'like', $request->blok . '-%');
                })

                ->when($request->status == 1, function ($q) {
                    $q->whereHas('customer');
                })

                ->orderBy('kode_kavling', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('customer', function ($row) {
                    return optional($row->customer)->nama_lengkap ?? '';
                })

                ->addColumn('lokasi', function ($row) {
                    $namaLokasi = optional($row->lokasi)->nama_kavling ?? '-';

                    if (optional($row->lokasi)->is_cluster == 1) {
                        $kodeKavling = $row->cluster . '-' . $row->no ?? '-';
                    } else {
                        $kodeKavling = $row->kode_kavling ?? '-';
                    }

                    return '<strong>' . $namaLokasi . '</strong><br>' . $kodeKavling;
                })

                ->editColumn('hrg_jual', function ($row) {
                    return '
                    <div class="d-flex justify-content-between w-100">
                        <span>Rp.</span>
                        <span>' . number_format($row->hrg_jual, 0, ',', '.') . '</span>
                    </div>
                ';
                })

                ->addColumn('pembayaran', function ($row) {
                    $customerId = optional($row->customer)->id;

                    if (! $customerId) {
                        return '
                        <div class="d-flex justify-content-between w-100">
                            <span>Rp.</span>
                            <span>0</span>
                        </div>
                    ';
                    }

                    $jumlahBayar = Pemasukan::where('id_customer', $customerId)
                        ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
                        ->where('id_kategori_transaksi', '!=', 4)
                        ->sum('nominal');

                    return '
                    <div class="d-flex justify-content-between w-100">
                        <span>Rp.</span>
                        <span>' . number_format($jumlahBayar, 0, ',', '.') . '</span>
                    </div>
                ';
                })

                ->addColumn('pencairan', function ($row) {
                    $customerId = optional($row->customer)->id;

                    if (! $customerId) {
                        return '
                        <div class="d-flex justify-content-between w-100">
                            <span>Rp.</span>
                            <span>0</span>
                        </div>
                    ';
                    }

                    $pencairan = Pemasukan::where('id_customer', $customerId)
                        ->where('keterangan', 'NOT LIKE', 'GANTI NAMA%')
                        ->where('id_kategori_transaksi', 4)
                        ->sum('nominal');

                    return '
                    <div class="d-flex justify-content-between w-100">
                        <span>Rp.</span>
                        <span>' . number_format($pencairan, 0, ',', '.') . '</span>
                    </div>
                ';
                })

                ->addColumn('sbum', function ($row) {
                    $customerId = optional($row->customer)->id;

                    if (! $customerId) {
                        return '
                        <div class="d-flex justify-content-between w-100">
                            <span>Rp.</span>
                            <span>0</span>
                        </div>
                    ';
                    }

                    $sbum = Pemasukan::where('id_customer', $customerId)
                        ->where('keterangan', 'NOT LIKE', 'GANTI NAMA%')
                        ->where('id_kategori_transaksi', 21)
                        ->sum('nominal');

                    return '
                    <div class="d-flex justify-content-between w-100">
                        <span>Rp.</span>
                        <span>' . number_format($sbum, 0, ',', '.') . '</span>
                    </div>
                ';
                })

                ->addColumn('sisa', function ($row) {
                    $customerId = optional($row->customer)->id;

                    if (! $customerId) {
                        return '
                        <div class="d-flex justify-content-between w-100">
                            <span>Rp.</span>
                            <span>0</span>
                        </div>
                    ';
                    }

                    $totalTagihan = Piutang::where('id_customer', $customerId)->sum('nominal');
                    $jumlahBayar  = Pemasukan::where('id_customer', $customerId)
                        ->where('keterangan', 'NOT LIKE', 'Biaya ganti nama%')
                        ->sum('nominal');

                    $sisaBayar = $totalTagihan - $jumlahBayar;

                    return '
                    <div class="d-flex justify-content-between w-100">
                        <span>Rp.</span>
                        <span>' . number_format($sisaBayar, 0, ',', '.') . '</span>
                    </div>
                ';
                })

                ->addColumn('action', function ($row) {
                    $customerId = optional($row->customer)->id;

                    $detailUrl = $customerId
                        ? route('pembayaran.show', $customerId)
                        : null;

                    $btn = '<div class="d-flex justify-content-center">';

                    if (! empty($customerId)) {
                        $btn .= '
                        <button class="btn btn-primary btn-sm mx-1 bayar-button"
                            data-id="' . e($customerId) . '"
                            data-toggle="modal"
                            data-target="#modalForm">
                            Bayar
                        </button>
                    ';
                    } else {
                        $btn .= '<button class="btn btn-secondary btn-sm mx-1" disabled>Bayar</button>';
                    }

                    if (! empty($customerId)) {
                        $btn .= '<a href="' . $detailUrl . '" class="btn btn-success btn-sm mx-1">Detail</a>';
                    } else {
                        $btn .= '<button class="btn btn-secondary btn-sm mx-1" disabled>Detail</button>';
                    }

                    $btn .= '</div>';

                    return $btn;
                })

                ->rawColumns([
                    'lokasi',
                    'hrg_jual',
                    'pembayaran',
                    'pencairan',
                    'sbum',
                    'sisa',
                    'action',
                ])
                ->make(true);
        }

        $bloks = KavlingPeta::selectRaw("DISTINCT SUBSTRING_INDEX(kode_kavling, '-', 1) as blok")
            ->orderBy('blok')
            ->pluck('blok');

        return view('admin.pembayaran.rekap', compact('pembayaran', 'lokasi', 'bankList', 'metodeBayar', 'bloks'));
    }
}
