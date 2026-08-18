<?php
namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateNumberController;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Customer;
use App\Models\Pemasukan;
use App\Models\PPJB;
use App\Services\DocumentGenerator;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PPJBController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = PPJB::with(
                'customer',
                'customer.lokasi',
                'customer.kavling'
            )
                ->whereHas('customer', function ($q) {
                    $q->where('stt_arsip', 0);
                })
                ->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal_ppjb', function ($row) {
                    return Carbon::parse($row->tanggal_ppjb)->translatedFormat('d F Y');
                })
                ->addColumn('lokasi_rumah', function ($row) {
                    $lokasi  = $row->customer->lokasi->nama_kavling ?? '-';
                    $kavling = $row->customer->kavling->kode_kavling ?? '-';
                    return '<strong>' . $lokasi . '</strong><br>' . $kavling;
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $deleteUrl = route('ppjb.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $documents = json_encode([
                            ['name' => 'Cetak PPJB', 'route' => route('ppjb.cetak', $row->id_customer), 'checked' => true],
                        ]);
                        $btn .= '<button class="btn btn-dark btn-sm btn-cetak-item mr-2"
                                        data-id="' . $row->id . '"
                                        data-nama="' . e($row->customer->nama_lengkap ?? '') . '"
                                        data-documents=\'' . $documents . '\'>Cetak</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">'
                        . csrf_field()
                        . method_field('DELETE')
                            . '<button type="submit" class="delete-button btn btn-danger btn-sm">Hapus</button></form>';
                    }

                    return $btn . '</div>';
                })
                ->rawColumns(['lokasi_rumah', 'action'])
                ->make(true);
        }

        $customerList = Customer::whereNotIn('id_status_progres', [1, 2, 6, 7])->get();

        return view('admin.transaksi.ppjb.index', compact('permissions', 'customerList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_ppjb' => 'required',
            'id_customer'  => 'required',
            'termin'       => 'required',
        ], [
            'tanggal_ppjb.required' => 'Tanggal PPJB wajib diisi.',
            'id_customer.required'  => 'Customer wajib dipilih.',
            'termin.required'       => 'Termin wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::with('lokasi')
                ->lockForUpdate()
                ->findOrFail($request->id_customer);

            $generator = new GenerateNumberController();

            $noPPJB = $generator->generateNomorDokumen(
                $customer->lokasi,
                'no_ppjb',
                PPJB::class
            );

            $sisaBayar = str_replace('.', '', $request->sisa_bayar);
            $termin    = (int) str_replace('.', '', $request->termin);
            $accPlafon = str_replace('.', '', $request->acc_plafon);

            $bayarPerBulan = $termin > 0 ? $sisaBayar / $termin : 0;

            $ppjb = PPJB::create([
                'tanggal_ppjb'    => $request->tanggal_ppjb,
                'id_customer'     => $request->id_customer,
                'termin'          => $termin,
                'acc_plafon'      => $accPlafon,
                'sisa_bayar'      => $sisaBayar,
                'bayar_per_bulan' => $bayarPerBulan,
                'no_ppjb'         => $noPPJB,
            ]);

            $this->logCreate('PPJB', $ppjb->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'no_ppjb' => $noPPJB,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store PPJB: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function cetakPPJB($id_customer)
    {
        $customer = Customer::with(['kavlingPeta.lokasi', 'kavlingPeta.perusahaan', 'lokasiKavling'])
            ->findOrFail($id_customer);
        $ppjb = PPJB::where('id_customer', $id_customer)->firstOrFail();

        $pembayaranList = Pemasukan::where('id_customer', $id_customer)->get();

        $tanggal          = Carbon::parse($ppjb->tanggal_ppjb)->locale('id');
        $tanggalTerbilang = ucwords($this->numberToWords($tanggal->day));
        $hari             = $ppjb->hari_bast ?? $tanggal->translatedFormat('l');
        $bulan            = $tanggal->translatedFormat('F');
        $tahun            = $tanggal->format('Y');
        $tahun_terbilang  = ucwords($this->numberToWords($tahun));

        $blockData = [];

        foreach ($pembayaranList as $i => $item) {
            $blockData[] = [
                'keterangan' => $item->keterangan ?? '-',
                'nominal'    => number_format($item->nominal, 0, ',', '.'),
                'tanggal_pembayaran' => $item->tanggal
                    ? strtolower(
                        Carbon::parse($item->tanggal)
                            ->locale('id')
                            ->translatedFormat('j F Y')
                    )
                    : '-',
            ];
        }

        if (empty($blockData)) {
            $blockData[] = [
                'keterangan' => '-',
                'nominal'    => '-',
                'tanggal_pembayaran' => '-',
            ];
        }

        $extra = [
            'no_ppjb'         => $ppjb->no_ppjb,
            'hari'            => $hari,
            'tanggal'         => $tanggalTerbilang,
            'bulan'           => $bulan,
            'tahun_terbilang' => $tahun_terbilang,
            'tanggal_ppjb'    => $tanggal->translatedFormat('j F Y'),
        ];

        return DocumentGenerator::generateDocxWithBlock(
            templatePath: public_path('templates/template_ppjb.docx'),
            customer: $customer,
            blockName: 'pembayaran_block',
            blockData: $blockData,
            extraValues: $extra,
            outputFilename: 'ppjb_' . Str::slug($customer->nama_lengkap) . '.docx'
        );
    }

    private function numberToWords($number)
    {
        $f = new \NumberFormatter("id", \NumberFormatter::SPELLOUT);
        return $f->format($number);
    }

    public function destroy($id)
    {
        $data = PPJB::findOrFail($id);

        $this->logDelete('PPJB', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
