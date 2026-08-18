<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\ArsipCustomer;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\CustomerTempo;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\MarketingOffline;
use App\Models\PersyaratanLegal;
use App\Models\ProgresListPenjualan;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DocumentTemplate;
use App\Services\DocumentDataContext;
use App\Services\DocumentGenerator;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        $docTemplates = DocumentTemplate::where('is_active', true)->get(['kode', 'nama']);

        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = Customer::with([
                'marketing',
                'lokasi',
                'kavling',
                'progres',
            ])
                ->where('stt_arsip', 0);

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('tgl_terima', function ($row) {
                    $tgl  = $row->tanggal_verif ? Carbon::parse($row->tanggal_verif)->translatedFormat('d F Y') : '-';
                    $kode = $row->kode_customer ? '<strong>' . $row->kode_customer . '</strong>' : '';

                    $jenisPembelian = $row->jenis_pembelian
                        ? '<div><small><strong>' . strtoupper($row->jenis_pembelian) . '</strong></small></div>'
                        : '';

                    return "$tgl<br>$kode<br>$jenisPembelian";
                })
                ->editColumn('id_marketing', function ($row) {
                    return $row->marketing->nama_marketing ?? '<span class="badge bg-danger"> ' . 'None Marketing' . '</span>';
                })

                ->editColumn('id_lokasi', function ($row) {
                    $namaLokasi  = $row->lokasi->nama_kavling ?? '-';
                    $kodeKavling = $row->kavling->kode_kavling ?? '-';
                    return '<strong>' . $namaLokasi . '</strong><br> ' . $kodeKavling;
                })

                ->editColumn('id_status_progres', function ($row) {
                    $status      = $row->progres->status_progres ?? '-';
                    $ketCashback = $row->progres->ket_cashback ?? '';

                    $badgeColors = [
                        'BOOKING FEE'  => 'warning',
                        'WAWANCARA'    => 'secondary',
                        'SP3K'         => 'success',
                        'AKAD'         => 'info',
                        'SERAH TERIMA' => 'dark',
                    ];

                    if (array_key_exists($status, $badgeColors)) {
                        $statusDisplay = '<span class="badge bg-' . $badgeColors[$status] . '">' . $status . '</span>';
                    } else {
                        $statusDisplay = $status;
                    }

                    $cashbackText = $ketCashback ? '<br><small>' . $ketCashback . '</small>' : '';

                    return $statusDisplay . $cashbackText;
                })
                ->editColumn('nama_lengkap', function ($row) {
                    $nama = '<strong>' . $row->nama_lengkap . '</strong>';
                    $wa   = $row->no_telp ?? '-';
                    $ktp  = $row->nik ? '<span class="badge bg-info">NIK: ' . $row->nik . '</span>' : '';
                    return "$nama<br>$wa<br>$ktp";
                })
                ->addColumn('action', function ($row) use ($permissions, $docTemplates): string {
                    $editUrl   = route('customer.edit', $row->id);
                    $uploadUrl = route('upload-file.index', ['id_customer' => $row->id]);
                    $btn       = '<div class="text-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button> ';
                    }

                    $btn .= '<a href="' . e($uploadUrl) . '" class="btn btn-info btn-sm">Upload File</a>';

                    $documents = $docTemplates->map(fn($t) => [
                        'name' => $t->nama,
                        'route' => route('customer.print-document', [$t->kode, $row->id]),
                        'checked' => true,
                    ])->values()->toJson();
                    $btn .= '<button class="btn btn-dark btn-sm btn-cetak-item ml-1"
                                    data-id="' . $row->id . '"
                                    data-nama="' . e($row->nama_lengkap) . '"
                                    data-documents=\'' . $documents . '\'>Cetak</button>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns([
                    'tgl_terima',
                    'nama_lengkap',
                    'id_marketing',
                    'id_lokasi',
                    'id_status_progres',
                    'action',
                ])

                ->make(true);
        }

        $marketing = MarketingOffline::all();
        $bank      = Bank::all();
        $progres   = ProgresListPenjualan::all();
        $lokasi    = LokasiKavling::all();

        $user = Auth::user();

        return view('admin.customer.customer.index', compact('permissions', 'marketing', 'lokasi', 'progres', 'bank', 'user'));
    }

    public function getKavling($idLokasi)
    {
        $data = KavlingPeta::where('id_lokasi', $idLokasi)
            ->select('id', 'kode_kavling')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_lengkap'        => 'required',
            'nik'                 => 'required',
            'jenis_kelamin'       => 'required',
            'no_telp'             => 'required',
            'alamat_domisili'     => 'required',
            'alamat_ktp'          => 'required',
            'pekerjaan'           => 'required',
            'id_lokasi'           => 'required',
            'id_kavling'          => 'required',
            'id_status_progres'   => 'required',
            'jenis_pembelian'     => 'required',
            'pembayaran_booking'  => 'required_if:jenis_pembelian,Booking',
            'tgl_batas_booking'   => 'required_if:jenis_pembelian,Booking',
            'pembayaran_cash'     => 'required_if:jenis_pembelian,Pembelian Cash',
            'an_surat_cash'       => 'required_if:jenis_pembelian,Pembelian Cash',
            'dp_cash_b'           => 'required_if:jenis_pembelian,Cash Bertahap',
            'termin_x_cash_b'     => 'required_if:jenis_pembelian,Cash Bertahap',
            'dp_kredit'           => 'required_if:jenis_pembelian,Kredit',
            'lama_cicilan_kredit' => 'required_if:jenis_pembelian,Kredit',
            'cicilan_kredit'      => 'required_if:jenis_pembelian,Kredit',
            'tgl_tempo_cicilan_1' => 'required_if:jenis_pembelian,Kredit',
            'an_surat_kredit'     => 'required_if:jenis_pembelian,Kredit',
        ];

        $messages = [
            'nama_lengkap.required'           => 'Nama lengkap wajib diisi!',
            'nik.required'                    => 'No. KTP wajib diisi!',
            'jenis_kelamin.required'          => 'Jenis kelamin wajib diisi!',
            'no_telp.required'                => 'No. Telp / WA wajib diisi!',
            'alamat_domisili.required'        => 'Alamat domisili wajib diisi!',
            'alamat_ktp.required'             => 'Alamat KTP wajib diisi!',
            'pekerjaan.required'              => 'Pekerjaan wajib diisi!',
            'id_lokasi.required'              => 'Lokasi perumahan wajib dipilih!',
            'id_kavling.required'             => 'Kavling wajib dipilih!',
            'id_status_progres.required'      => 'Status progres wajib dipilih!',
            'jenis_pembelian.required'        => 'Jenis pembelian wajib dipilih!',
            'pembayaran_booking.required_if'  => 'Pembayaran Booking wajib diisi!',
            'tgl_batas_booking.required_if'   => 'Tanggal Batas Booking wajib diisi!',
            'pembayaran_cash.required_if'     => 'Pembayaran Cash wajib diisi!',
            'an_surat_cash.required_if'       => 'Atas Nama Surat wajib diisi!',
            'dp_cash_b.required_if'           => 'DP wajib diisi!',
            'termin_x_cash_b.required_if'     => 'Termin wajib diisi!',
            'dp_kredit.required_if'           => 'DP / Uang Muka wajib diisi!',
            'lama_cicilan_kredit.required_if' => 'Lama Cicilan wajib diisi!',
            'cicilan_kredit.required_if'      => 'Cicilan Per Bulan wajib diisi!',
            'tgl_tempo_cicilan_1.required_if' => 'Tanggal Tempo Cicilan Pertama wajib diisi!',
            'an_surat_kredit.required_if'     => 'Atas Nama Surat wajib diisi!',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $latest = Customer::latest('kode_customer')->first();
            if ($latest && preg_match('/GDI-(\d+)/', $latest->kode_customer, $match)) {
                $number  = (int) $match[1] + 1;
                $newKode = 'GDI-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            } else {
                $newKode = 'GDI-0001';
            }

            $data = [
                'kode_customer'       => $newKode,
                'tanggal_verif'       => Carbon::now("Asia/Jakarta"),
                'id_lokasi'           => $request->id_lokasi,
                'id_kavling'          => $request->id_kavling,
                'id_status_progres'   => $request->id_status_progres,
                'nama_lengkap'        => $request->nama_lengkap,
                'nik'                 => $request->nik,
                'nik_p'               => $request->nik_p ?? '',
                'jenis_kelamin'       => $request->jenis_kelamin ?? '',
                'tempat_lahir'        => $request->tempat_lahir ?? '',
                'tgl_lahir'           => $request->tgl_lahir ?? null,
                'alamat_ktp'          => $request->alamat_ktp ?? '',
                'alamat_domisili'     => $request->alamat_domisili ?? '',
                'no_telp'             => $request->no_telp ?? '',
                'email'               => $request->email ?? '',
                'npwp'                => $request->npwp ?? null,
                'pekerjaan'           => $request->pekerjaan ?? '',
                'ket_cashback'        => $request->ket_cashback ?? '',
                'id_marketing'        => $request->id_marketing ?? 0,
                'jenis_pembelian'     => $request->jenis_pembelian,
                'pembayaran_booking'  => str_replace('.', '', $request->pembayaran_booking ?? 0),
                'tgl_batas_booking'   => $request->tgl_batas_booking ?? null,
                'ket_booking'         => $request->ket_booking ?? '',
                'diskon_cash'         => str_replace('.', '', $request->diskon_cash ?? 0),
                'pembayaran_cash'     => str_replace('.', '', $request->pembayaran_cash ?? 0),
                'sisa_bayar_ajb'      => str_replace('.', '', $request->sisa_bayar_ajb ?? 0),
                'an_surat_cash'       => $request->an_surat_cash ?? '',
                'dp_cash_b'           => str_replace('.', '', $request->dp_cash_b ?? 0),
                'termin_x_cash_b'     => str_replace('.', '', $request->termin_x_cash_b ?? 0),
                'dp_kredit'           => str_replace('.', '', $request->dp_kredit ?? 0),
                'lama_cicilan_kredit' => $request->lama_cicilan_kredit ?? 0,
                'cicilan_kredit'      => str_replace('.', '', $request->cicilan_kredit ?? 0),
                'tgl_tempo_cicilan_1' => $request->tgl_tempo_cicilan_1 ?? null,
                'an_surat_kredit'     => $request->an_surat_kredit ?? '',
            ];

            $customer = Customer::create($data);
            $this->logCreate('Customer', $customer->id);

            PersyaratanLegal::create([
                'id_customer' => $customer->id,
            ]);
            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $list = Customer::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Customer::findOrFail($id);

        $rules = [
            'nama_lengkap'    => 'required',
            'nik'             => 'required',
            'npwp'            => 'required',
            'tempat_lahir'    => 'required',
            'tgl_lahir'       => 'required|date',
            'no_telp'         => 'required',
            'jenis_kelamin'   => 'required',
            'alamat_ktp'      => 'required',
            'alamat_domisili' => 'required',
        ];

        $messages = [
            'nama_lengkap.required'    => 'Nama lengkap wajib diisi!',
            'nik.required'             => 'NIK wajib diisi!',
            'npwp.required'            => 'NPWP wajib diisi!',
            'tempat_lahir.required'    => 'Tempat lahir wajib diisi!',
            'tgl_lahir.required'       => 'Tanggal lahir wajib diisi!',
            'no_telp.required'         => 'No. Telp / WA wajib diisi!',
            'jenis_kelamin.required'   => 'Jenis kelamin wajib diisi!',
            'alamat_ktp.required'      => 'Alamat KTP wajib diisi!',
            'alamat_domisili.required' => 'Alamat Domisili wajib diisi!',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $user = Auth::user();

            $updateData = [
                'nama_lengkap'      => $request->nama_lengkap,
                'nik'               => $request->nik,
                'tempat_lahir'      => $request->tempat_lahir,
                'tgl_lahir'         => $request->tgl_lahir,
                'no_telp'           => $request->no_telp,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'alamat_ktp'        => $request->alamat_ktp,
                'alamat_domisili'   => $request->alamat_domisili,
                'email'             => $request->email ?? null,
                'npwp'              => $request->npwp,
                'pekerjaan'         => $request->pekerjaan ?? null,
                'no_bpjs_kes'       => $request->no_bpjs_kes ?? null,
                'status_pernikahan' => $request->status_pernikahan ?? null,
                'nama_p'            => $request->nama_p ?? null,
                'nik_p'             => $request->nik_p ?? null,
                'nama_saudara'      => $request->nama_saudara ?? null,
                'no_telp_saudara'   => $request->no_telp_saudara ?? null,
            ];

            if ($user->id_role == 2) {
                $existingData = $data->toArray();
                unset($existingData['id']);

                $tempoData = array_merge($existingData, $updateData, [
                    'id_customer' => $data->id,
                    'id_user'     => $user->id,
                ]);

                CustomerTempo::create($tempoData);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'tempo'  => true,
                ]);
            } else {
                $data->update($updateData);
                $this->logEdit('Customer', $data->id);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'tempo'  => false,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = Customer::findOrFail($id);

            $berkas = PersyaratanLegal::where('id_customer', $id)->get();
            if ($berkas->count()) {
                foreach ($berkas as $b) {
                    if (! empty($b->percakapan_wa) && file_exists(public_path('assets/legal/pengajuan_berkas/percakapan_wa/' . $b->percakapan_wa))) {
                        unlink(public_path('assets/legal/pengajuan_berkas/percakapan_wa/' . $b->percakapan_wa));
                    }
                    $b->delete();
                }
            }

            ArsipCustomer::create([
                'id_customer'       => $data->id,
                'tanggal'           => Carbon::now('Asia/Jakarta'),
                'id_lokasi'         => $data->id_lokasi,
                'id_kavling'        => $data->id_kavling,
                'id_status_progres' => $data->id_status_progres,
                'kode_customer'     => $data->kode_customer,
                'nama_lengkap'      => $data->nama_lengkap,
                'nik'               => $data->nik,
                'nik_p'             => $data->nik_p,
                'jenis_kelamin'     => $data->jenis_kelamin,
                'tempat_lahir'      => $data->tempat_lahir,
                'tgl_lahir'         => $data->tgl_lahir,
                'alamat_ktp'        => $data->alamat_ktp,
                'alamat_domisili'   => $data->alamat_domisili,
                'no_telp'           => $data->no_telp,
                'pekerjaan'         => $data->pekerjaan,
                'id_marketing'      => $data->id_marketing,
            ]);

            $kavling = KavlingPeta::find($data->id_kavling);
            if ($kavling) {
                $kavling->status      = 0;
                $kavling->id_customer = 0;
                $kavling->save();
            }

            $this->logDelete('Customer', $data->id);
            $data->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data customer berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus customer: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function cetakData(Request $request)
    {
        $query = Customer::with(['marketing', 'lokasi', 'kavling', 'progres', 'bank']);

        if ($request->lokasi) {
            $query->where('id_lokasi', $request->lokasi);
        }

        $data = $query->get();

        if ($request->tipe == 1) {
            return $this->cetakExcel($data);
        } else {
            return $this->cetakPdf($data);
        }
    }

    private function cetakPdf($data)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4');
        $pdf->SetTitle('Data Customer');
        $pdf->AddPage();

        $logoPath = public_path('assets/img/header.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 20, 5, 30);
        }

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'PT. HAMZAH MAJU BERSAMA', 0, 1, 'C');

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(190, 7, 'developer & kontraktor', 0, 1, 'C');

        $pdf->Line(10, 30, 200, 30);
        $pdf->Line(10, 31, 200, 31);

        $pdf->Ln(15);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->SetTextColor(218, 0, 0);
        $pdf->Cell(190, 8, 'DATA CUSTOMER', 0, 1, 'C');
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 9);
        $pdf->Ln(5);
        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetFillColor(252, 203, 53);
        $pdf->Cell(10, 7, 'NO', 1, 0, 'C', true);
        $pdf->Cell(45, 7, 'PEMOHON', 1, 0, 'L', true);
        $pdf->Cell(15, 7, 'UNIT', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'NO. TELP', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'MARKETING', 1, 0, 'L', true);
        $pdf->Cell(25, 7, 'BANK', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'STATUS', 1, 1, 'C', true);
        $pdf->SetFont('Times', '', 8);
        $no = 1;
        if ($data->count() > 0) {
            foreach ($data as $d) {
                $pdf->Cell(10, 7, $no++, 1, 0, 'C');
                $pdf->Cell(45, 7, $d->nama_lengkap, 1, 0, 'L');
                $pdf->Cell(15, 7, ($d->kavling->kode_kavling ?? '-'), 1, 0, 'C');
                $pdf->Cell(25, 7, $d->no_telp, 1, 0, 'C');
                $pdf->Cell(40, 7, ($d->marketing->nama_marketing ?? '-'), 1, 0, 'L');
                $pdf->Cell(25, 7, ($d->bank->nama ?? '-'), 1, 0, 'C');
                $pdf->Cell(30, 7, ($d->progres->status_progres ?? '-'), 1, 1, 'C');
            }
        } else {
            $pdf->Cell(190, 7, 'Tidak ada data ditemukan', 1, 1, 'C');
        }

        return response($pdf->Output('data_customer.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="data_customer.pdf"');
    }

    private function cetakExcel($data)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Set judul dokumen
        $sheet->setCellValue('A1', 'LAPORAN DATA CUSTOMER');
        $sheet->mergeCells('A1:G1');

        // Style untuk judul
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => [
                'bold'  => true,
                'size'  => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill'      => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E86AB'],
            ],
        ]);

        // Tambahkan informasi tanggal
        $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => [
                'italic' => true,
                'size'   => 10,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Header kolom
        $headers = [
            'A4' => 'NO',
            'B4' => 'NAMA PEMOHON',
            'C4' => 'UNIT/KAVLING',
            'D4' => 'NO. TELEPON',
            'E4' => 'MARKETING',
            'F4' => 'BANK',
            'G4' => 'STATUS',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style untuk header
        $sheet->getStyle('A4:G4')->applyFromArray([
            'font'      => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill'      => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Data rows
        $row = 5;
        $no  = 1;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $d->nama_lengkap);
            $sheet->setCellValue('C' . $row, $d->kavling->kode_kavling ?? '-');
            $sheet->setCellValue('D' . $row, $d->no_telp);
            $sheet->setCellValue('E' . $row, $d->marketing->nama_marketing ?? '-');
            $sheet->setCellValue('F' . $row, $d->bank->nama ?? '-');
            $sheet->setCellValue('G' . $row, $d->progres->status_progres ?? '-');

            // Style untuk baris data (zebra striping)
            $fillColor = ($no % 2 == 0) ? 'F9F9F9' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                'fill'    => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            $row++;
        }

        // Auto-resize kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

                                                       // Set minimum width untuk kolom tertentu
        $sheet->getColumnDimension('B')->setWidth(25); // Nama
        $sheet->getColumnDimension('C')->setWidth(15); // Unit
        $sheet->getColumnDimension('D')->setWidth(15); // Telp
        $sheet->getColumnDimension('E')->setWidth(20); // Marketing
        $sheet->getColumnDimension('F')->setWidth(20); // Bank
        $sheet->getColumnDimension('G')->setWidth(15); // Status

        // Set tinggi baris untuk header
        $sheet->getRowDimension('1')->setRowHeight(30);
        $sheet->getRowDimension('4')->setRowHeight(25);

        // Footer dengan total data
        $totalRow = $row;
        $sheet->setCellValue('A' . $totalRow, 'Total Data: ' . ($no - 1) . ' record');
        $sheet->mergeCells('A' . $totalRow . ':G' . $totalRow);
        $sheet->getStyle('A' . $totalRow)->applyFromArray([
            'font'      => [
                'bold'   => true,
                'italic' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill'      => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E3F2FD'],
            ],
        ]);

        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'data_customer_' . date('Y-m-d_H-i-s') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    public function cetakFormSubsidi($id_customer)
    {
        $customer = Customer::with(['kavlingPeta.lokasi', 'kavlingPeta.perusahaan', 'lokasiKavling'])
            ->findOrFail($id_customer);

        return DocumentGenerator::generateDocx(
            templatePath: public_path('templates/form_subsidi.docx'),
            customer: $customer,
            outputFilename: $customer->kode_customer . '_' . $customer->nama_lengkap . '.docx'
        );
    }

    public function printDocument($templateCode, $idCustomer)
    {
        $template = DocumentTemplate::where('kode', $templateCode)->where('is_active', true)->firstOrFail();

        $customer = Customer::with(['kavlingPeta.lokasi', 'kavlingPeta.perusahaan', 'lokasiKavling'])
            ->findOrFail($idCustomer);

        return DocumentGenerator::generateFromTemplate(
            template: $template,
            customer: $customer
        );
    }

    public function getTempo(Request $request)
    {
        Carbon::setLocale('id');
        $docTemplates = DocumentTemplate::where('is_active', true)->get(['kode', 'nama']);

        if ($request->ajax()) {
            $data = CustomerTempo::with([
                'marketing',
                'lokasi',
                'kavling',
                'progres',
            ]);

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('tgl_terima', function ($row) {
                    $tgl  = $row->tanggal_verif ? Carbon::parse($row->tanggal_verif)->translatedFormat('d F Y') : '-';
                    $kode = $row->kode_customer ? '<strong>' . $row->kode_customer . '</strong>' : '';

                    $jenisPembelian = $row->jenis_pembelian
                        ? '<div><small><strong>' . strtoupper($row->jenis_pembelian) . '</strong></small></div>'
                        : '';

                    return "$tgl<br>$kode<br>$jenisPembelian";
                })
                ->editColumn('id_marketing', function ($row) {
                    return $row->marketing->nama_marketing ?? '<span class="badge bg-danger"> ' . 'None Marketing' . '</span>';
                })

                ->editColumn('id_lokasi', function ($row) {
                    $namaLokasi  = $row->lokasi->nama_kavling ?? '-';
                    $kodeKavling = $row->kavling->kode_kavling ?? '-';
                    return '<strong>' . $namaLokasi . '</strong><br> ' . $kodeKavling;
                })

                ->editColumn('id_status_progres', function ($row) {
                    $status      = $row->progres->status_progres ?? '-';
                    $ketCashback = $row->progres->ket_cashback ?? '';

                    $badgeColors = [
                        'BOOKING FEE'  => 'warning',
                        'WAWANCARA'    => 'secondary',
                        'SP3K'         => 'success',
                        'AKAD'         => 'info',
                        'SERAH TERIMA' => 'dark',
                    ];

                    if (array_key_exists($status, $badgeColors)) {
                        $statusDisplay = '<span class="badge bg-' . $badgeColors[$status] . '">' . $status . '</span>';
                    } else {
                        $statusDisplay = $status;
                    }

                    $cashbackText = $ketCashback ? '<br><small>' . $ketCashback . '</small>' : '';

                    return $statusDisplay . $cashbackText;
                })
                ->editColumn('nama_lengkap', function ($row) {
                    $nama = '<strong>' . $row->nama_lengkap . '</strong>';
                    $wa   = $row->no_telp ?? '-';
                    $ktp  = $row->nik ? '<span class="badge bg-info">NIK: ' . $row->nik . '</span>' : '';
                    return "$nama<br>$wa<br>$ktp";
                })
                ->addColumn('action', function ($row) use ($docTemplates): string {
                    $editUrl   = route('customer.show-tempo', $row->id);
                    $uploadUrl = route('upload-file.index', ['id_customer' => $row->id]);
                    $btn       = '<div class="text-center">';

                    $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Detail</button> ';

                    $documents = $docTemplates->map(fn($t) => [
                        'name' => $t->nama,
                        'route' => route('customer.print-document', [$t->kode, $row->id]),
                        'checked' => true,
                    ])->values()->toJson();
                    $btn .= '<button class="btn btn-dark btn-sm btn-cetak-item ml-1"
                                    data-id="' . $row->id . '"
                                    data-nama="' . e($row->nama_lengkap) . '"
                                    data-documents=\'' . $documents . '\'>Cetak</button>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns([
                    'tgl_terima',
                    'nama_lengkap',
                    'id_marketing',
                    'id_lokasi',
                    'id_status_progres',
                    'action',
                ])

                ->make(true);
        }

        $marketing = MarketingOffline::all();
        $bank      = Bank::all();
        $progres   = ProgresListPenjualan::all();
        $lokasi    = LokasiKavling::all();

        return view('admin.customer.customer.tempo', compact('marketing', 'lokasi', 'progres', 'bank'));
    }

    public function showTempo($id)
    {
        $list = CustomerTempo::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function postTempo(Request $request, $id)
    {
        $data = CustomerTempo::findOrFail($id);

        $rules = [
            'nama_lengkap'    => 'required',
            'nik'             => 'required',
            'npwp'            => 'required',
            'tempat_lahir'    => 'required',
            'tgl_lahir'       => 'required|date',
            'no_telp'         => 'required',
            'jenis_kelamin'   => 'required',
            'alamat_ktp'      => 'required',
            'alamat_domisili' => 'required',
        ];

        $messages = [
            'nama_lengkap.required'    => 'Nama lengkap wajib diisi!',
            'nik.required'             => 'NIK wajib diisi!',
            'npwp.required'            => 'NPWP wajib diisi!',
            'tempat_lahir.required'    => 'Tempat lahir wajib diisi!',
            'tgl_lahir.required'       => 'Tanggal lahir wajib diisi!',
            'no_telp.required'         => 'No. Telp / WA wajib diisi!',
            'jenis_kelamin.required'   => 'Jenis kelamin wajib diisi!',
            'alamat_ktp.required'      => 'Alamat KTP wajib diisi!',
            'alamat_domisili.required' => 'Alamat Domisili wajib diisi!',
        ];

        $requestData = $data->toArray();
        $request->merge($requestData);

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($data->id_customer);

            $updateData = [
                'nama_lengkap'      => $data->nama_lengkap,
                'nik'               => $data->nik,
                'tempat_lahir'      => $data->tempat_lahir,
                'tgl_lahir'         => $data->tgl_lahir,
                'no_telp'           => $data->no_telp,
                'jenis_kelamin'     => $data->jenis_kelamin,
                'alamat_ktp'        => $data->alamat_ktp,
                'alamat_domisili'   => $data->alamat_domisili,
                'email'             => $data->email,
                'npwp'              => $data->npwp,
                'pekerjaan'         => $data->pekerjaan,
                'no_bpjs_kes'       => $data->no_bpjs_kes,
                'status_pernikahan' => $data->status_pernikahan,
                'nama_p'            => $data->nama_p,
                'nik_p'             => $data->nik_p,
                'nama_saudara'      => $data->nama_saudara,
                'no_telp_saudara'   => $data->no_telp_saudara,
            ];

            $customer->update($updateData);
            $this->logEdit('Customer', $customer->id);

            $data->delete();

            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Data Customer berhasil diupdate dari Customer Tempo',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

}
