<?php
namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\KategoriTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;
use Yajra\DataTables\Facades\DataTables;

class LaporanArusKasController extends Controller
{
    public function index()
    {
        $monthList = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $rekeningList = Bank::all();

        return view('admin.keuangan.laporan_arus_kas.index', compact('monthList', 'rekeningList'));
    }

    public function filter(Request $request)
    {
        try {
            Carbon::setLocale('id');

            $tahun    = $request->tahun;
            $bulan    = $request->bulan;
            $rekening = $request->rekening;

            $pemasukan = DB::table('pemasukan')
                ->whereYear('tanggal', $tahun)
                ->when($bulan != 0, fn($q) => $q->whereMonth('tanggal', $bulan))
                ->where('id_bank', $rekening)
                ->select('id', 'tanggal', 'id_kategori_transaksi', 'nominal', DB::raw("'pemasukan' as tipe"));

            $pengeluaran = DB::table('pengeluaran')
                ->whereYear('tanggal', $tahun)
                ->when($bulan != 0, fn($q) => $q->whereMonth('tanggal', $bulan))
                ->where('id_bank', $rekening)
                ->select('id', 'tanggal', 'id_kategori_transaksi', 'nominal', DB::raw("'pengeluaran' as tipe"));

            $data    = $pemasukan->unionAll($pengeluaran);
            $results = DB::query()->fromSub($data, 'sub')->orderByDesc('tanggal')->get();

            $totalPemasukan   = $results->where('tipe', 'pemasukan')->sum('nominal');
            $totalPengeluaran = $results->where('tipe', 'pengeluaran')->sum('nominal');

            $kategoriMap = KategoriTransaksi::pluck('kategori', 'id')->toArray();
            $jenisMap    = KategoriTransaksi::pluck('jenis_kategori', 'id')->map(fn($v) => strtolower($v))->toArray();

            if ($request->ajax()) {
                return DataTables::of($results)
                    ->addIndexColumn()
                    ->addColumn('kategori', fn($row) => $kategoriMap[$row->id_kategori_transaksi] ?? 'Kategori Tidak Diketahui')
                    ->addColumn('tanggal', fn($row) => Carbon::parse($row->tanggal)->translatedFormat('d F Y'))
                    ->addColumn('debit', function ($row) use ($jenisMap) {
                        if (($jenisMap[$row->id_kategori_transaksi] ?? null) === 'pemasukan') {
                            $icon = '<i class="fa-solid fa-circle-plus mr-1 text-success"></i>';
                            return '
                            <div class="d-flex justify-content-between harga-format w-100">
                                <span>' . $icon . 'Rp.</span>
                                <span>' . number_format($row->nominal, 0, ',', '.') . '</span>
                            </div>';
                        }
                        return '';
                    })
                    ->addColumn('kredit', function ($row) use ($jenisMap) {
                        if (($jenisMap[$row->id_kategori_transaksi] ?? null) === 'pengeluaran') {
                            $icon = '<i class="fa-solid fa-circle-minus mr-1 text-danger"></i>';
                            return '
                            <div class="d-flex justify-content-between harga-format w-100">
                                <span>' . $icon . 'Rp.</span>
                                <span>' . number_format($row->nominal, 0, ',', '.') . '</span>
                            </div>';
                        }
                        return '';
                    })
                    ->with([
                        'total_pemasukan'   => $totalPemasukan,
                        'total_pengeluaran' => $totalPengeluaran,
                    ])
                    ->rawColumns(['debit', 'kredit'])
                    ->make(true);
            }

            return response()->json(['message' => 'Invalid request'], 400);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat memproses data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            Carbon::setLocale('id');

            $request->validate([
                'tahun'    => 'required|numeric',
                'bulan'    => 'required|numeric',
                'rekening' => 'required|numeric',
            ]);

            $tahun    = $request->tahun;
            $bulan    = $request->bulan;
            $rekening = $request->rekening;

            $bank = Bank::find($rekening)?->nama ?? 'Tidak Diketahui';

            $pemasukan = DB::table('pemasukan')
                ->whereYear('tanggal', $tahun)
                ->when($bulan != 0, fn($q) => $q->whereMonth('tanggal', $bulan))
                ->where('id_bank', $rekening)
                ->select('id', 'tanggal', 'id_kategori_transaksi', 'nominal', DB::raw("'PEMASUKAN' as tipe"));

            $pengeluaran = DB::table('pengeluaran')
                ->whereYear('tanggal', $tahun)
                ->when($bulan != 0, fn($q) => $q->whereMonth('tanggal', $bulan))
                ->where('id_bank', $rekening)
                ->select('id', 'tanggal', 'id_kategori_transaksi', 'nominal', DB::raw("'PENGELUARAN' as tipe"));

            $data = DB::query()->fromSub($pemasukan->unionAll($pengeluaran), 'sub')->orderByDesc('tanggal')->get();

            $kategoriMap = KategoriTransaksi::pluck('kategori', 'id')->toArray();

            $periode = $bulan == 0
                ? 'Tahun ' . $tahun
                : Carbon::createFromDate($tahun, $bulan)->translatedFormat('F Y');

            $pdf = new TCPDF();
            $pdf->SetMargins(10, 10, 10);
            $pdf->AddPage('P', 'A4');
            $pdf->SetFont('helvetica', '', 9);

            $html = '
        <h3 style="text-align: center;">Laporan Arus Kas</h3>
        <p style="text-align: center;">Periode : ' . $periode . '</p>
        <p style="text-align: center;">Rekening : ' . $bank . '</p>
        <br>
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <th width="30px" align="center">No</th>
                    <th width="80px" align="center">Tanggal</th>
                    <th width="150px" align="center">Kategori</th>
                    <th width="100px" align="center">Tipe</th>
                    <th width="100px" align="center">Nominal</th>
                </tr>
            </thead>
            <tbody>';

            $no = 1;
            foreach ($data as $row) {
                $kategori = $kategoriMap[$row->id_kategori_transaksi] ?? '-';
                $html .= '
                <tr>
                    <td align="center">' . $no++ . '</td>
                    <td>' . Carbon::parse($row->tanggal)->translatedFormat('d F Y') . '</td>
                    <td>' . $kategori . '</td>
                    <td>' . ucfirst(strtolower($row->tipe)) . '</td>
                    <td align="right">' . number_format($row->nominal, 0, ',', '.') . '</td>
                </tr>';
            }

            if ($no === 1) {
                $html .= '<tr><td colspan="5" align="center">Tidak ada data</td></tr>';
            }

            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output('laporan_arus_kas.pdf');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            Carbon::setLocale('id');

            $request->validate([
                'tahun'    => 'required|integer',
                'bulan'    => 'required|integer',
                'rekening' => 'required|integer',
            ]);

            $tahun    = $request->tahun;
            $bulan    = $request->bulan;
            $rekening = $request->rekening;

            $bank = Bank::find($rekening)?->nama ?? 'Tidak Diketahui';

            $pemasukan = DB::table('pemasukan')
                ->whereYear('tanggal', $tahun)
                ->when($bulan != 0, fn($q) => $q->whereMonth('tanggal', $bulan))
                ->where('id_bank', $rekening)
                ->select('id', 'tanggal', 'id_kategori_transaksi', 'nominal', DB::raw("'pemasukan' as tipe"));

            $pengeluaran = DB::table('pengeluaran')
                ->whereYear('tanggal', $tahun)
                ->when($bulan != 0, fn($q) => $q->whereMonth('tanggal', $bulan))
                ->where('id_bank', $rekening)
                ->select('id', 'tanggal', 'id_kategori_transaksi', 'nominal', DB::raw("'pengeluaran' as tipe"));

            $results = DB::query()->fromSub($pemasukan->unionAll($pengeluaran), 'sub')->orderByDesc('tanggal')->get();

            $kategoriMap = KategoriTransaksi::pluck('kategori', 'id')->toArray();

            $periode = $bulan == 0
                ? 'Tahun ' . $tahun
                : Carbon::createFromDate($tahun, $bulan)->translatedFormat('F Y');

            $spreadsheet = new Spreadsheet();
            $sheet       = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Laporan Arus Kas');
            $sheet->setCellValue('A2', 'Periode : ' . $periode);
            $sheet->setCellValue('A3', 'Rekening : ' . $bank);

            $sheet->mergeCells('A1:E1');
            $sheet->mergeCells('A2:E2');
            $sheet->mergeCells('A3:E3');

            $headers = ['No', 'Tanggal', 'Kategori', 'Tipe', 'Nominal'];
            $sheet->fromArray($headers, null, 'A5');

            $row = 6;
            $no  = 1;

            foreach ($results as $item) {
                $sheet->setCellValue("A$row", $no++);
                $sheet->setCellValue("B$row", Carbon::parse($item->tanggal)->translatedFormat('d F Y'));
                $sheet->setCellValue("C$row", $kategoriMap[$item->id_kategori_transaksi] ?? '-');
                $sheet->setCellValue("D$row", ucfirst($item->tipe));
                $sheet->setCellValueExplicit("E$row", $item->nominal, DataType::TYPE_NUMERIC);
                $row++;
            }

            $lastRow = $row - 1;
            $range   = 'A5:E' . $lastRow;

            $sheet->getStyle($range)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => '000000'],
                    ],
                ],
            ]);

            $sheet->getStyle('A5:E5')->getFont()->setBold(true);
            $sheet->getStyle('A5:E5')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE699');

            $sheet->getStyle("E6:E$lastRow")
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = 'laporan_arus_kas.xlsx';
            $writer   = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            $writer->save("php://output");
            exit;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Gagal membuat Excel: ' . $e->getMessage());
        }
    }

}
