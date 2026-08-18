<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Customer;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\KomponenBiaya;
use App\Models\Piutang;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class KavlingController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = KavlingPeta::with('lokasi');

            if ($request->id_lokasi && $request->id_lokasi != 0) {
                $data->where('id_lokasi', $request->id_lokasi);
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('panjang', function ($row) {
                    return '
                <p>Pjg Kanan : <strong>' . $row->panjang_kanan . ' m</strong></p>
                <p>Pjg Kiri : <strong>' . $row->panjang_kiri . ' m</strong></p>
            ';
                })
                ->addColumn('lebar', function ($row) {
                    return '
                <p>Lebar Depan: <strong>' . $row->lebar_depan . ' m</strong></p>
                <p>Lebar Belakang: <strong>' . $row->lebar_belakang . ' m</strong></p>
            ';
                })
                ->addColumn('luas', function ($row) {
                    return '
                <p>Luas Tanah: <strong>' . $row->luas_tanah . ' m</strong></p>
                <p>Luas Bangunan: <strong>' . $row->luas_bangunan . ' m</strong></p>
            ';
                })
                ->editColumn('rincian_harga', fn($row) => $row->rincian_harga_html)

                ->addColumn('total_harga', function ($row) {
                    $total = $row->total_harga;

                    return '
        <div class="d-flex justify-content-between harga-format w-100">
            <span>Rp.</span>
            <span>' . number_format($total, 0, ',', '.') . '</span>
        </div>
    ';
                })

                ->addColumn('id_lokasi', fn($row) => $row->lokasi?->nama_kavling ?? '-')
                ->addColumn('action', function ($row) use ($permissions): string {
                    $editUrl = route('kavling.edit', $row->id);
                    $showUrl = route('kavling.show', $row->id);

                    $btn = '<div class="text-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button mr-1" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';
                        $btn .= '<button class="btn btn-success btn-sm foto-button" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Foto</button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['panjang', 'lebar', 'luas', 'rincian_harga', 'total_harga', 'action', 'id_lokasi'])
                ->make(true);
        }

        $lokasiList = LokasiKavling::all();

        return view('admin.master.kavling.index', compact('permissions', 'lokasiList'));
    }

    public function edit($id)
    {
        $data = KavlingPeta::with('lokasi')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }
    public function show($id)
    {
        $data = KavlingPeta::with('lokasi')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = KavlingPeta::findOrFail($id);

        $rules = [
            'panjang_kanan'  => 'required',
            'panjang_kiri'   => 'required',
            'lebar_depan'    => 'required',
            'lebar_belakang' => 'required',
            'luas_tanah'     => 'required',
            'luas_bangunan'  => 'required',
            'hrg_meter'      => 'required',
            'tipe_bangunan'  => 'required',
            'rincian_biaya'  => 'nullable|array',
        ];

        $messages = [
            'panjang_kanan.required'  => 'Panjang kanan wajib diisi.',
            'panjang_kiri.required'   => 'Panjang kiri wajib diisi.',
            'lebar_depan.required'    => 'Lebar depan wajib diisi.',
            'lebar_belakang.required' => 'Lebar belakang wajib diisi.',
            'luas_tanah.required'     => 'Luas tanah wajib diisi.',
            'luas_bangunan.required'  => 'Luas bangunan wajib diisi.',
            'hrg_meter.required'      => 'Harga per meter wajib diisi.',
            'tipe_bangunan.required'  => 'Tipe rumah wajib diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // Parse rincian_biaya dari form dinamis [{nama, nilai, update_semua}, ...]
            $rincianRaw = $request->rincian_biaya ?? [];
            $rincian = [];

            foreach ($rincianRaw as $item) {
                $nama  = trim($item['nama'] ?? '');
                $nilai = (int) str_replace('.', '', $item['nilai'] ?? 0);
                if ($nama === '') continue;

                $rincian[$nama] = [
                    'nama'         => $nama,
                    'nilai'        => max($nilai, 0),
                    'update_semua' => !empty($item['update_semua']),
                ];
            }

            $rincian = array_values($rincian);

            // Auto-sync rincian_biaya ke komponen_biaya
            $existingKode = KomponenBiaya::pluck('kode_unik');
            $existingNama = KomponenBiaya::pluck('nama');
            $maxUrutan    = KomponenBiaya::max('urutan') ?? 0;
            foreach ($rincian as $item) {
                $nama = $item['nama'];
                if (!$existingNama->contains($nama)) {
                    $kode = Str::slug($nama, '_');
                    if ($existingKode->contains($kode)) {
                        $kode = $kode . '_' . uniqid();
                    }
                    KomponenBiaya::create([
                        'kode_unik' => $kode,
                        'nama'      => $nama,
                        'deskripsi' => '',
                        'urutan'    => ++$maxUrutan,
                        'wajib'     => false,
                        'aktif'     => true,
                        'satuan'    => 'Rp',
                    ]);
                    $existingKode->push($kode);
                    $existingNama->push($nama);
                }
            }

            $hrgJualBaru     = 0;
            $biayaSuratBaru  = 0;
            $peningkatanBaru = 0;
            foreach ($rincian as $item) {
                if ($item['nama'] === 'Harga Rumah')        $hrgJualBaru     = $item['nilai'];
                if ($item['nama'] === 'Biaya Surat')        $biayaSuratBaru  = $item['nilai'];
                if ($item['nama'] === 'Peningkatan Mutu')   $peningkatanBaru = $item['nilai'];
            }

            $rincianUntukSimpan = array_map(fn ($item) => [
                'nama'  => $item['nama'],
                'nilai' => $item['nilai'],
            ], $rincian);

            $db = [
                'panjang_kanan'     => $request->panjang_kanan,
                'panjang_kiri'      => $request->panjang_kiri,
                'lebar_depan'       => $request->lebar_depan,
                'lebar_belakang'    => $request->lebar_belakang,
                'luas_tanah'        => $request->luas_tanah,
                'luas_bangunan'     => $request->luas_bangunan,
                'hrg_meter'         => str_replace('.', '', $request->hrg_meter ?? 0),
                'tipe_bangunan'     => str_replace('.', '', $request->tipe_bangunan ?? 0),
                'hrg_jual'          => $hrgJualBaru,
                'biaya_surat'       => $biayaSuratBaru,
                'peningkatan_mutu'  => $peningkatanBaru,
                'daya_listrik'      => str_replace('.', '', $request->daya_listrik ?? 0),
                'keterangan'        => $request->keterangan ?? '',
                'no_sertifikat'     => $request->no_sertifikat ?? '',
                'rincian_biaya'     => $request->has('rincian_biaya')
                    ? (!empty($rincianUntukSimpan) ? $rincianUntukSimpan : null)
                    : $data->rincian_biaya,
            ];

            $data->update($db);
            $this->syncRincianBiayaKavling($data->id, $rincian, $request->deleted_biaya ?? []);
            $this->logEdit('Kavling', $data->id);

            $syncFields = [
                ['baru' => $hrgJualBaru,      'keyword' => 'Harga Rumah', 'kode_unik' => 'harga_jual', 'id_comp' => \App\Models\KomponenBiaya::where('kode_unik', 'harga_jual')->value('id')],
                ['baru' => $biayaSuratBaru,    'keyword' => 'Biaya Surat', 'kode_unik' => 'biaya_surat', 'id_comp' => \App\Models\KomponenBiaya::where('kode_unik', 'biaya_surat')->value('id')],
                ['baru' => $peningkatanBaru,   'keyword' => 'Peningkatan Mutu', 'kode_unik' => 'peningkatan_mutu', 'id_comp' => \App\Models\KomponenBiaya::where('kode_unik', 'peningkatan_mutu')->value('id')],
            ];

            foreach ($syncFields as $field) {
                if (!$field['id_comp']) continue;
                $customers = Customer::where('id_kavling', $data->id)->get();
                foreach ($customers as $cust) {
                    $piutang = Piutang::where('id_customer', $cust->id)
                        ->where(function ($q) use ($field) {
                            $q->where('id_komponen_biaya', $field['id_comp'])
                              ->orWhere('deskripsi', 'like', '%' . $field['keyword'] . '%');
                        })
                        ->first();
                    if ($piutang && $piutang->nominal != $field['baru']) {
                        $sisaBayarBaru = $field['baru'] - $piutang->terbayar;

                        $piutang->update([
                            'nominal'           => $field['baru'],
                            'sisa_bayar'        => $sisaBayarBaru,
                            'id_komponen_biaya' => $field['id_comp'],
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    private function syncRincianBiayaKavling(int $editedId, array $submittedRincian, array $deletedNames): void
    {
        $deletedNames = collect($deletedNames)
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique()
            ->values();

        $submittedByName = collect($submittedRincian)
            ->keyBy('nama');

        KavlingPeta::select('id', 'rincian_biaya')
            ->orderBy('id')
            ->chunkById(100, function ($kavlings) use ($editedId, $submittedByName, $deletedNames) {
                foreach ($kavlings as $kavling) {
                    $existing = collect($kavling->rincian_biaya ?? [])
                        ->filter(fn ($item) => isset($item['nama']))
                        ->reject(fn ($item) => $deletedNames->contains($item['nama']))
                        ->keyBy('nama');

                    foreach ($submittedByName as $nama => $item) {
                        $shouldUseSubmittedValue = $kavling->id === $editedId || !empty($item['update_semua']);

                        if ($shouldUseSubmittedValue) {
                            $existing[$nama] = [
                                'nama'  => $nama,
                                'nilai' => (int) $item['nilai'],
                            ];
                            continue;
                        }

                        if (! $existing->has($nama)) {
                            $existing[$nama] = [
                                'nama'  => $nama,
                                'nilai' => 0,
                            ];
                        }
                    }

                    $rincian = $existing->values()->toArray();
                    $nilaiByNama = collect($rincian)->keyBy('nama');

                    $kavling->update([
                        'rincian_biaya'    => !empty($rincian) ? $rincian : null,
                        'hrg_jual'         => (int) ($nilaiByNama->get('Harga Rumah')['nilai'] ?? 0),
                        'biaya_surat'      => (int) ($nilaiByNama->get('Biaya Surat')['nilai'] ?? 0),
                        'peningkatan_mutu' => (int) ($nilaiByNama->get('Peningkatan Mutu')['nilai'] ?? 0),
                    ]);
                }
            });
    }

    public function updateFoto(Request $request, $id)
    {
        $data = KavlingPeta::findOrFail($id);

        $rules = [
            'foto' => ($data->foto == null || $data->foto == '')
                ? 'required|mimes:jpg,jpeg,png,webp|max:2048'
                : 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = [
            'foto.required' => 'Foto wajib diupload jika belum ada.',
            'foto.mimes'    => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto.max'      => 'Ukuran foto maksimal 2 MB.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $ext  = $foto->getClientOriginalExtension();

                $filename = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/foto_kavling/'), $filename);
            }

            $db = [
                'foto' => $filename ?? $data->foto,
            ];

            $data->update($db);

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function cetakPdf(Request $request, $id_lokasi)
    {
        $query = KavlingPeta::with('lokasi');

        if ($id_lokasi && $id_lokasi != 0) {
            $query->where('id_lokasi', $id_lokasi);
        }

        $data = $query->get();

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor('App');
        $pdf->SetTitle('Data Kavling');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 14);
        $namaLokasi = 'Semua Lokasi';

        if ($request->id_lokasi && $request->id_lokasi != 0) {
            $lokasi = LokasiKavling::find($request->id_lokasi);
            $namaLokasi = $lokasi ? $lokasi->nama_kavling : 'Semua Lokasi';
        }

        $pdf->Cell(0, 10, 'Data Kavling ' . $namaLokasi, 0, 1, 'C');

        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(220, 220, 220);

        $pdf->Cell(10, 10, 'No', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Perumahan', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Kode Kavling', 1, 0, 'C', true);
        $pdf->Cell(55, 10, 'Panjang', 1, 0, 'C', true);
        $pdf->Cell(55, 10, 'Lebar', 1, 0, 'C', true);
        $pdf->Cell(55, 10, 'Luas', 1, 0, 'C', true);
        $pdf->Cell(35, 10, 'Harga', 1, 1, 'C', true);

        $pdf->SetFont('helvetica', '', 9);
        $no = 1;

        foreach ($data as $row) {
            $pdf->Cell(10, 10, $no++, 1, 0, 'C');
            $pdf->Cell(40, 10, $row->lokasi?->nama_kavling ?? '-', 1, 0);
            $pdf->Cell(30, 10, $row->kode_kavling ?? '-', 1, 0);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(55, 10,
                "Kanan: {$row->panjang_kanan} m\nKiri: {$row->panjang_kiri} m",
                1, 'L', false, 0, '', '', true, 0, false, true, 10, 'M'
            );
            $pdf->MultiCell(55, 10,
                "Depan: {$row->lebar_depan} m\nBelakang: {$row->lebar_belakang} m",
                1, 'L', false, 0, '', '', true, 0, false, true, 10, 'M'
            );
            $pdf->MultiCell(55, 10,
                "Tanah: {$row->luas_tanah} m²\nBangunan: {$row->luas_bangunan} m²",
                1, 'L', false, 0, '', '', true, 0, false, true, 10, 'M'
            );

            $pdf->Cell(35, 10, 'Rp ' . number_format($row->hrg_jual, 0, ',', '.'), 1, 1, 'R');
        }

        $pdf->Output('data_kavling.pdf', 'I');
    }

    public function cetakExcel(Request $request, $id_lokasi)
    {
        $query = KavlingPeta::with('lokasi');

        if ($id_lokasi && $id_lokasi != 0) {
            $query->where('id_lokasi', $id_lokasi);
        }

        $data = $query->get();

        // Ambil komponen biaya aktif sebagai referensi kolom dinamis
        $komponenBiaya = KomponenBiaya::aktif()->urut()->get();
        $namaBiayaList = $komponenBiaya->pluck('nama')->toArray();

        $staticCols = 6; // No, Perumahan, Kode Kavling, Panjang, Lebar, Luas
        $totalCols = $staticCols + count($namaBiayaList) + 1; // +1 untuk Total Harga
        $lastColLetter = Coordinate::stringFromColumnIndex($totalCols);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $lokasiNama = '';
        if ($id_lokasi && $id_lokasi != 0) {
            $lokasi = LokasiKavling::find($id_lokasi);
            $lokasiNama = $lokasi ? $lokasi->nama_kavling : 'Lokasi #' . $id_lokasi;
        } else {
            $lokasiNama = 'Semua Lokasi';
        }
        $sheet->setTitle('Data Kavling ' . $lokasiNama);

        // ── Baris 1: Judul ──────────────────────────────────────
        $sheet->setCellValue('A1', 'Data Kavling');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // ── Baris 2: Header Kolom ───────────────────────────────
        $headers = array_merge(
            ['No', 'Perumahan', 'Kode Kavling', 'Panjang', 'Lebar', 'Luas'],
            $namaBiayaList,
            ['Total Harga']
        );

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '2', $header);
            $sheet->getStyle($col . '2')->getFont()->setBold(true);
            $sheet->getStyle($col . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // ── Baris 3+: Data ─────────────────────────────────────
        $rowNum = 3;
        $no = 1;

        foreach ($data as $row) {
            $namaLokasi = $row->lokasi?->nama_kavling ?? '-';
            $sheet->setCellValue("A{$rowNum}", $no++);
            $sheet->setCellValue("B{$rowNum}", $namaLokasi);
            $sheet->setCellValue("C{$rowNum}", $row->kode_kavling ?? '-');

            // Kolom D: Panjang (multi-line, format lama)
            $sheet->setCellValue("D{$rowNum}",
                "Kanan: {$row->panjang_kanan} m\nKiri: {$row->panjang_kiri} m");
            // Kolom E: Lebar (multi-line)
            $sheet->setCellValue("E{$rowNum}",
                "Depan: {$row->lebar_depan} m\nBelakang: {$row->lebar_belakang} m");
            // Kolom F: Luas (multi-line)
            $sheet->setCellValue("F{$rowNum}",
                "Tanah: {$row->luas_tanah} m²\nBangunan: {$row->luas_bangunan} m²");

            $sheet->getStyle("D{$rowNum}:F{$rowNum}")->getAlignment()->setWrapText(true);
            $sheet->getStyle("A{$rowNum}:F{$rowNum}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // ── Kolom Komponen Biaya Dinamis ────────────────────
            $rincian = collect($row->rincian_biaya ?? [])->keyBy('nama');

            $firstColNum = 7;
            $colNum = $firstColNum;
            foreach ($namaBiayaList as $nama) {
                $nilai = (int) ($rincian->get($nama)['nilai'] ?? 0);
                $colLetter = Coordinate::stringFromColumnIndex($colNum);
                $sheet->setCellValue($colLetter . $rowNum, $nilai);
                $sheet->getStyle($colLetter . $rowNum)
                    ->getNumberFormat()->setFormatCode('#,##0');
                $colNum++;
            }

            // ── Total Harga ─────────────────────────────────────
            $firstCol = Coordinate::stringFromColumnIndex($firstColNum);
            $lastCol = Coordinate::stringFromColumnIndex($colNum - 1);
            $totalCol = Coordinate::stringFromColumnIndex($colNum);
            $sheet->setCellValue($totalCol . $rowNum,
                "=SUM({$firstCol}{$rowNum}:{$lastCol}{$rowNum})");
            $sheet->getStyle($totalCol . $rowNum)
                ->getNumberFormat()->setFormatCode('#,##0');

            $rowNum++;
        }

        // ── Styling ─────────────────────────────────────────────
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle("A2:{$lastColLetter}" . ($rowNum - 1))->applyFromArray($styleArray);

        foreach (range(2, $rowNum - 1) as $r) {
            $sheet->getRowDimension($r)->setRowHeight(-1);
        }

        // ── Output ──────────────────────────────────────────────
        $fileName = 'data_kavling.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$fileName}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file')->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, false, false);

        // Row 0 = judul, Row 1 = header kolom, Row 2+ = data
        array_shift($rows); // skip judul
        $headers = array_shift($rows) ?? [];
        if (empty($rows)) {
            return back()->withErrors(['msg' => 'File Excel kosong.']);
        }

        // ── Deteksi format berdasarkan data (bukan header) ──────────────
        $isNewFormat = false;
        foreach ($rows as $row) {
            $val = $row[3] ?? null;
            if ($val !== null && $val !== '') {
                $isNewFormat = is_numeric($val);
                break;
            }
        }
        $biayaStartIdx = $isNewFormat ? 9 : 6;

        $totalCols = count($headers);
        $colMap = [];
        $staticHeaderNames = [
            'No', 'Perumahan', 'Kode Kavling', 'Panjang', 'Lebar', 'Luas',
            'Pjg Kanan (m)', 'Pjg Kiri (m)', 'Lb Depan (m)', 'Lb Belakang (m)',
            'Luas Tanah (m2)', 'Luas Bangunan (m2)', 'Total Harga',
        ];
        for ($i = $biayaStartIdx; $i < $totalCols - 1; $i++) {
            $nama = trim($headers[$i] ?? '');
            if ($nama !== '' && !in_array($nama, $staticHeaderNames)) {
                $colMap[$i] = $nama;
            }
        }

        // Auto-sync header biaya ke komponen_biaya
        $existingKode = KomponenBiaya::pluck('kode_unik');
        $existingNama = KomponenBiaya::pluck('nama');
        $maxUrutan    = KomponenBiaya::max('urutan') ?? 0;
        foreach ($colMap as $nama) {
            if (!$existingNama->contains($nama)) {
                $kode = Str::slug($nama, '_');
                if ($existingKode->contains($kode)) {
                    $kode = $kode . '_' . uniqid();
                }
                KomponenBiaya::create([
                    'kode_unik' => $kode,
                    'nama'      => $nama,
                    'deskripsi' => '',
                    'urutan'    => ++$maxUrutan,
                    'wajib'     => false,
                    'aktif'     => true,
                    'satuan'    => 'Rp',
                ]);
                $existingKode->push($kode);
                $existingNama->push($nama);
            }
        }

        DB::beginTransaction();
        try {
            $imported = 0;
            $errors = [];
            $processedKode = [];

            foreach ($rows as $rowIndex => $row) {
                // Skip baris kosong
                $hasData = false;
                foreach ($row as $cell) {
                    if ($cell !== null && $cell !== '') { $hasData = true; break; }
                }
                if (!$hasData) continue;

                $kodeKavling = trim((string) ($row[2] ?? ''));
                if (!$kodeKavling) {
                    $errors[] = "Baris " . ($rowIndex + 3) . ": Kode Kavling kosong, dilewati.";
                    continue;
                }

                // Skip duplikat kode_kavling (hanya proses baris pertama)
                if (in_array($kodeKavling, $processedKode)) {
                    $errors[] = "Baris " . ($rowIndex + 3) . " ('{$kodeKavling}'): dilewati (duplikat).";
                    continue;
                }
                $processedKode[] = $kodeKavling;

                $kavlings = KavlingPeta::where('kode_kavling', $kodeKavling)->get();
                if ($kavlings->isEmpty()) {
                    $errors[] = "Baris " . ($rowIndex + 3) . ": Kode '{$kodeKavling}' tidak ditemukan di database.";
                    continue;
                }

                // ── Parse Dimensi (berdasarkan record pertama untuk fallback) ─
                $first = $kavlings->first();
                if ($isNewFormat) {
                    $panjangKanan  = (isset($row[3]) && is_numeric($row[3]) && $row[3] >= 0) ? (float) $row[3] : $first->panjang_kanan;
                    $panjangKiri   = (isset($row[4]) && is_numeric($row[4]) && $row[4] >= 0) ? (float) $row[4] : $first->panjang_kiri;
                    $lebarDepan    = (isset($row[5]) && is_numeric($row[5]) && $row[5] >= 0) ? (float) $row[5] : $first->lebar_depan;
                    $lebarBelakang = (isset($row[6]) && is_numeric($row[6]) && $row[6] >= 0) ? (float) $row[6] : $first->lebar_belakang;
                    $luasTanah     = (isset($row[7]) && is_numeric($row[7]) && $row[7] >= 0) ? (float) $row[7] : $first->luas_tanah;
                    $luasBangunan  = (isset($row[8]) && is_numeric($row[8]) && $row[8] >= 0) ? (float) $row[8] : $first->luas_bangunan;
                } else {
                    $panjangKanan  = $first->panjang_kanan;
                    $panjangKiri   = $first->panjang_kiri;
                    $lebarDepan    = $first->lebar_depan;
                    $lebarBelakang = $first->lebar_belakang;
                    $luasTanah     = $first->luas_tanah;
                    $luasBangunan  = $first->luas_bangunan;

                    $panjangStr = trim((string) ($row[3] ?? ''));
                    if ($panjangStr && preg_match('/Kanan:\s*([\d,.]+)\s*m/i', $panjangStr, $m)) $panjangKanan = (float) str_replace(',', '.', $m[1]);
                    if ($panjangStr && preg_match('/Kiri:\s*([\d,.]+)\s*m/i',  $panjangStr, $m)) $panjangKiri  = (float) str_replace(',', '.', $m[1]);

                    $lebarStr = trim((string) ($row[4] ?? ''));
                    if ($lebarStr && preg_match('/Depan:\s*([\d,.]+)\s*m/i',    $lebarStr, $m)) $lebarDepan    = (float) str_replace(',', '.', $m[1]);
                    if ($lebarStr && preg_match('/Belakang:\s*([\d,.]+)\s*m/i', $lebarStr, $m)) $lebarBelakang = (float) str_replace(',', '.', $m[1]);

                    $luasStr = trim((string) ($row[5] ?? ''));
                    if ($luasStr && preg_match('/Tanah:\s*([\d,.]+)\s*m/i',    $luasStr, $m)) $luasTanah    = (float) str_replace(',', '.', $m[1]);
                    if ($luasStr && preg_match('/Bangunan:\s*([\d,.]+)\s*m/i', $luasStr, $m)) $luasBangunan = (float) str_replace(',', '.', $m[1]);
                }

                // ── Parse Rincian Biaya ────────────────────────────────
                // Parse dulu dari Excel (sama untuk semua duplikat)
                $biayaDariExcel = [];
                foreach ($colMap as $colIdx => $nama) {
                    $raw   = $row[$colIdx] ?? 0;
                    $nilai = (int) str_replace(['.', ',', ' '], '', (string) $raw);
                    $biayaDariExcel[$nama] = $nilai;
                }

                // Update SEMUA record dengan kode_kavling yg sama
                foreach ($kavlings as $kavling) {
                    $existing = $kavling->rincian_biaya ?? [];
                    $merged   = collect($existing)->keyBy('nama');

                    foreach ($biayaDariExcel as $nama => $nilai) {
                        $merged[$nama] = ['nama' => $nama, 'nilai' => $nilai];
                    }

                    $rincian = $merged->values()->toArray();

                    // Jika kosong, isi default 0 dari komponen_biaya
                    if (empty($rincian)) {
                        $defaults = KomponenBiaya::aktif()->urut()->get();
                        foreach ($defaults as $kb) {
                            $rincian[] = ['nama' => $kb->nama, 'nilai' => 0];
                        }
                    }

                    // Dual-write: sync old columns
                    $hrgJual = 0; $biayaSurat = 0; $peningkatanMutu = 0;
                    foreach ($rincian as $item) {
                        if ($item['nama'] === 'Harga Rumah')      $hrgJual        = $item['nilai'];
                        if ($item['nama'] === 'Biaya Surat')      $biayaSurat     = $item['nilai'];
                        if ($item['nama'] === 'Peningkatan Mutu') $peningkatanMutu = $item['nilai'];
                    }

                    $kavling->update([
                        'panjang_kanan'    => $panjangKanan,
                        'panjang_kiri'     => $panjangKiri,
                        'lebar_depan'      => $lebarDepan,
                        'lebar_belakang'   => $lebarBelakang,
                        'luas_tanah'       => $luasTanah,
                        'luas_bangunan'    => $luasBangunan,
                        'hrg_jual'         => $hrgJual,
                        'biaya_surat'      => $biayaSurat,
                        'peningkatan_mutu' => $peningkatanMutu,
                        'rincian_biaya'    => !empty($rincian) ? $rincian : null,
                    ]);
                }

                $imported++;
            }

            DB::commit();

            return back()->with('success', "{$imported} kavling berhasil diimpor.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Import gagal: ' . $e->getMessage()]);
        }
    }
}
