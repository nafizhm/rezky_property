<?php
namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\MarketingOffline;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MarketingOfflineController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        if ($request->ajax()) {

            $data = MarketingOffline::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_marketing', function ($row) {
                    $iconUrl = ($row->foto != null && $row->foto != '')
                    ? asset('assets/marketing/marketing_offline/' . $row->foto)
                    : ($row->jenis_kelamin == 1
                        ? asset('assets/img/men-icon.png')
                        : asset('assets/img/women-icon.png'));

                    return '<img src="' . $iconUrl . '" alt="icon" style="width:30px; height:30px; border-radius:50%; margin-right:8px;">' . e($row->kode_marketing);
                })
                ->addColumn('alamat', function ($row) {
                    $alamat = e($row->alamat);
                    $noTelp = $row->no_telp
                    ? '<span class="badge badge-danger">No Telp : ' . e($row->no_telp) . '</span>'
                    : '';

                    return "{$alamat}" . ($noTelp ? "<br>{$noTelp}" : '');
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                    ? '<span class="badge badge-success">Aktif</span>'
                    : '<span class="badge badge-danger">Tidak Aktif</span>';
                })
                ->addColumn('rekening', function ($row) {
                    $nama_bank   = e($row->nama_bank);
                    $no_rekening = '<span class="badge badge-success">' . e($row->no_rekening) . '</span>';
                    $atas_nama   = $row->atas_nama ? '<small>An: ' . e($row->atas_nama) . '</small>' : '';

                    return "{$nama_bank}<br>{$no_rekening}" . ($atas_nama ? "<br>{$atas_nama}" : '');
                })
                ->addColumn('action', function ($row) use ($permissions): string {
                    $editUrl   = route('marketing-offline.edit', $row->id);
                    $deleteUrl = route('marketing-offline.destroy', $row->id);
                    $btn       = '<div class="text-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="delete-button btn btn-danger btn-sm">
                            Hapus
                        </button>
                     </form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'kode_marketing', 'alamat', 'status', 'rekening'])
                ->make(true);
        }

        return view('admin.marketing.marketing_offline.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_marketing' => 'required|unique:marketing_offline,nama_marketing',
        ];

        $messages = [
            'nama_marketing.required' => 'Nama marketing wajib diisi.',
            'nama_marketing.unique'   => 'Nama marketing sudah terdaftar.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $lastMarketing = DB::table('marketing_offline')
                ->where('kode_marketing', 'like', 'M-%')
                ->orderByDesc('id')
                ->first();

            $nextNumber = ($lastMarketing && preg_match('/M-(\d+)/', $lastMarketing->kode_marketing, $matches))
            ? intval($matches[1]) + 1
            : 1;

            $kodeMarketing = 'M-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $ext  = $foto->getClientOriginalExtension();

                $filename = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/marketing/marketing_offline/'), $filename);
            }

            $marketing = MarketingOffline::create([
                'kode_marketing' => $kodeMarketing,
                'nama_marketing' => $request->nama_marketing,
                'alamat'         => $request->alamat ?? '',
                'jenis_kelamin'  => $request->jenis_kelamin ?? 1,
                'pekerjaan'      => $request->pekerjaan ?? '',
                'no_telp'        => $request->no_telp ?? '',
                'foto'           => $filename ?? '',
                'status'         => $request->status ?? 1,
                'sosmed'         => $request->sosmed ?? '',
                'nama_bank'      => $request->nama_bank ?? '',
                'no_rekening'    => $request->no_rekening ?? '',
                'atas_nama'      => $request->atas_nama ?? '',
            ]);

            $this->logCreate('Marketing', $marketing->id);

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

    public function edit($id)
    {
        $data = MarketingOffline::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = MarketingOffline::findOrFail($id);

        $rules = [
            'nama_marketing' => 'required|unique:marketing_offline,nama_marketing,' . $data->id . ',id',
        ];

        $messages = [
            'nama_marketing.required' => 'Nama marketing wajib diisi.',
            'nama_marketing.unique'   => 'Nama marketing sudah terdaftar.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            if ($request->hasFile('foto')) {
                if (! empty($data->foto) && file_exists(public_path('assets/marketing/marketing_offline/' . $data->foto))) {
                    unlink(public_path('assets/marketing/marketing_offline/' . $data->foto));
                }

                $foto     = $request->file('foto');
                $ext      = $foto->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/marketing/marketing_offline/'), $filename);
            }

            $db = [
                'nama_marketing' => $request->nama_marketing,
                'alamat'         => $request->alamat ?? '',
                'jenis_kelamin'  => $request->jenis_kelamin ?? 1,
                'pekerjaan'      => $request->pekerjaan ?? '',
                'no_telp'        => $request->no_telp ?? '',
                'foto'           => $filename ?? '',
                'status'         => $request->status ?? 1,
                'sosmed'         => $request->sosmed ?? '',
                'nama_bank'      => $request->nama_bank ?? '',
                'no_rekening'    => $request->no_rekening ?? '',
                'atas_nama'      => $request->atas_nama ?? '',
            ];

            $data->update($db);
            $this->logEdit('Marketing', $data->id);

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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = MarketingOffline::findOrFail($id);

            if (! empty($data->foto) && file_exists(public_path('assets/marketing/marketing_offline/' . $data->foto))) {
                unlink(public_path('assets/marketing/marketing_offline/' . $data->foto));
            }

            $this->logDelete('Marketing', $data->id);
            $data->delete();

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
}

