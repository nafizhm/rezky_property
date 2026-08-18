<?php
namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\BankKPR;
use App\Models\Customer;
use App\Models\Notaris;
use App\Models\Wawancara;
use App\Models\WawancaraSp3k;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

Carbon::setLocale('id');

class WawancaraController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        app(AkadController::class)->refreshDeadlineAkad();

        if ($request->ajax()) {
            $data = Wawancara::with(['customer', 'customer.lokasi', 'customer.kavling', 'bankKPR'])
                ->where('status', 1)
                ->whereHas('customer', function ($q) {
                    $q->where('stt_arsip', 0);
                })
                ->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id_customer', fn($row) => $row->customer->nama_lengkap ?? '-')
                ->addColumn('lokasi_rumah', function ($row) {
                    $kode = $row->customer->kavling->kode_kavling ?? '-';
                    $nama = $row->customer->lokasi->nama_kavling ?? '-';
                    return "$kode - $nama";
                })
                ->addColumn('tgl_wawancara', function ($row) {
                    return $row->tgl_wawancara
                        ? Carbon::parse($row->tgl_wawancara)->locale('id')->translatedFormat('d F Y')
                        : '-';
                })
                ->addColumn('catatan_wawancara', function ($row) {
                    return $row->catatan_wawancara
                        ? strip_tags($row->catatan_wawancara)
                        : '-';
                })
                ->addColumn('id_bank_kpr', fn($row) => $row->bankKPR?->nama ?? '-')
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('wawancara.edit', $row->id);
                    $deleteUrl = route('wawancara.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-warning text-white btn-xs acc-bank-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Acc Bank</button>';
                        $btn .= '<button class="btn btn-primary btn-xs mx-1 edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">'
                        . csrf_field()
                        . method_field('DELETE')
                            . '<button type="submit" class="delete-button btn btn-danger btn-xs">Hapus</button></form>';
                    }

                    return $btn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $customerList = Customer::where('stt_arsip', 0)->get();
        $bankKPRList  = BankKPR::all();
        $hari         = Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('l');
        $notarisList  = Notaris::all();

        return view('admin.transaksi.wawancara.index', compact(
            'permissions',
            'customerList',
            'bankKPRList',
            'hari',
            'notarisList'
        ));
    }

    public function detailCustomer($id_customer)
    {
        try {
            $data = Customer::with([
                'lokasi',
                'kavling',
                'marketing',
                'piutangs',
                'wawancara.wawancaraSp3k' => function ($query) {
                    $query->where('status', 1)->orderByDesc('id');
                },
            ])->find($id_customer);

            if ($data) {
                $sp3k = null;

                // cari SP3K terakhir dari semua wawancara
                foreach ($data->wawancara as $waw) {
                    $sp = $waw->wawancaraSp3k->first(); // sudah diorder desc
                    if ($sp) {
                        $sp3k = $sp;
                        break; // ambil yang pertama ditemukan (id terbesar)
                    }
                }

                $sisa_bayar = $data->piutangs->sum('sisa_bayar') - ($sp3k->acc_plafon ?? 0);

                return response()->json([
                    'nik'                   => $data->nik,
                    'alamat_ktp'            => $data->alamat_ktp,
                    'lokasi_rumah'          => $data->kavling->kode_kavling . ' - ' . $data->lokasi->nama_kavling,
                    'tipe_bangunan'         => $data->kavling->tipe_bangunan,
                    'luas_tanah'            => $data->kavling->luas_tanah,
                    'luas_bangunan'         => $data->kavling->luas_bangunan,
                    'nama_marketing'        => $data->marketing->nama_marketing,
                    'wawancara_sp3k_id'     => $sp3k ? $sp3k->id : null,
                    'wawancara_sp3k_status' => $sp3k ? $sp3k->status : null,
                    'wawancara_acc_plafon'  => $sp3k ? $sp3k->acc_plafon : null,
                    'sisa_bayar'            => $sisa_bayar,
                ]);
            }

            return response()->json([], 404);

        } catch (\Exception $e) {
            Log::error('Error detailCustomer: ' . $e->getMessage(), [
                'id_customer' => $id_customer,
                'trace'       => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data customer.',
            ], 500);
        }
    }

    public function edit($id)
    {
        $list = Wawancara::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => [
                 ...$list->toArray(),
                'hari_wawancara' => Carbon::parse($list->tgl_wawancara)->locale('id')->translatedFormat('l'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_wawancara'     => 'required',
            'id_customer'       => 'required',
            'catatan_wawancara' => 'nullable',
            'id_bank_kpr'       => 'required',
        ], [
            'tgl_wawancara.required' => 'Tanggal wawancara wajib diisi.',
            'id_customer.required'   => 'Customer wajib dipilih.',
            'id_bank_kpr.required'   => 'Bank KPR wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $wawancara = Wawancara::create([
                'tgl_wawancara'     => $request->tgl_wawancara,
                'id_bank_kpr'       => $request->id_bank_kpr,
                'id_customer'       => $request->id_customer,
                'catatan_wawancara' => $request->catatan_wawancara ?? '',
                'status'            => 1,
            ]);

            $customer                    = Customer::find($request->id_customer);
            $customer->id_status_progres = 7;
            $customer->save();

            $this->logCreate('Wawancara', $wawancara->id);

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

    public function update(Request $request, $id)
    {
        $wawancara = Wawancara::findOrFail($id);

        $request->validate([
            'tgl_wawancara'     => 'required',
            'catatan_wawancara' => 'nullable',
            'id_bank_kpr'       => 'required',
        ], [
            'tgl_wawancara.required' => 'Tanggal wawancara wajib diisi.',
            'id_bank_kpr.required'   => 'Bank KPR wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $wawancara->update([
                'tgl_wawancara'     => $request->tgl_wawancara,
                'id_bank_kpr'       => $request->id_bank_kpr,
                'catatan_wawancara' => $request->catatan_wawancara ?? '',
            ]);

            $this->logEdit('Wawancara', $wawancara->id);

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function simpanSp3k(Request $request, $id)
    {
        $request->validate([
            'acc_plafon'      => 'required',
            'tenor'           => 'required',
            'tgl_terbit_sp3k' => 'required',
            'no_sp3k'         => 'required',
            'id_notaris'      => 'required',
            'catatan_acc'     => 'nullable',
            'lampiran'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'acc_plafon.required'      => 'Acc Plafon wajib diisi.',
            'tenor.required'           => 'Tenor wajib diisi.',
            'tgl_terbit_sp3k.required' => 'Tanggal Terbit SP3K wajib diisi.',
            'no_sp3k.required'         => 'No SP3K wajib diisi.',
            'id_notaris.required'      => 'Notaris wajib diisi.',
            'lampiran.required'        => 'Lampiran wajib diisi.',
            'lampiran.file'            => 'Lampiran harus berupa file.',
            'lampiran.mimes'           => 'Lampiran harus berformat JPG, JPEG, PNG, atau PDF.',
            'lampiran.max'             => 'Ukuran lampiran maksimal 2 MB.',
        ]);

        DB::beginTransaction();
        try {
            $tglTerbit  = Carbon::parse($request->tgl_terbit_sp3k);
            $tglExpired = $tglTerbit->copy()->addDays(90);

            $wawancara = Wawancara::with('customer')->findOrFail($id);

            if ($request->hasFile('lampiran')) {

                $file = $request->file('lampiran');
                $ext  = $file->getClientOriginalExtension();

                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/SP3K/'), $filename);
            }

            WawancaraSp3k::create([
                'id_wawancara'    => $id,
                'id_bank_kpr'     => $wawancara->id_bank_kpr,
                'acc_plafon'      => str_replace('.', '', $request->acc_plafon),
                'tenor'           => str_replace('.', '', $request->tenor),
                'id_notaris'      => $request->id_notaris,
                'tgl_terbit_sp3k' => $tglTerbit,
                'lampiran'        => $filename,
                'no_sp3k'         => $request->no_sp3k,
                'tgl_expired'     => $tglExpired,
                'catatan_acc'     => $request->catatan_acc ?? '',
                'status'          => 1,
            ]);

            $wawancara->update([
                'status' => 2,
            ]);

            $wawancara->customer->update([
                'id_status_progres' => 4,
            ]);

            $this->logEdit('Wawancara ACC BANK', $id);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data SP3K berhasil disimpan.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving SP3K data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $data = Wawancara::with('customer')->findOrFail($id);

        $data->customer->update([
            'id_status_progres' => 2,
        ]);

        $this->logDelete('Wawancara', $id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }

}
