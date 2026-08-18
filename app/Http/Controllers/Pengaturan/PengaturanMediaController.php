<?php
namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\PengaturanMedia;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PengaturanMediaController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        if ($request->ajax()) {

            $data = PengaturanMedia::where('stt_aktif', 1)
                ->orderBy('urutan')
                ->orderBy('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('preview', function ($row) {
                    $url = asset('config_media/' . $row->nama_file);

                    return '<img src="' . $url . '" height="50">';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl = route('pengaturan-media.edit', $row->id);
                    $btn     = '<div class="text-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'preview'])
                ->make(true);
        }

        return view('admin.pengaturan.pengaturan_media.index', compact('permissions'));
    }

    public function edit($id)
    {
        $data = PengaturanMedia::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = PengaturanMedia::findOrFail($id);

        $rules = [
            'nama_file' => 'required|mimes:jpg,jpeg,png,webp,ico|max:10240',
        ];

        $messages = [
            'nama_file.required' => 'File wajib diisi.',
            'nama_file.mimes'    => 'File harus berformat JPG, JPEG, PNG, WEBP, atau ICO.',
            'nama_file.max'      => 'Ukuran file maksimal 10 MB.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            if ($request->hasFile('nama_file')) {
                if (! empty($data->nama_file) && file_exists(public_path('config_media/' . $data->nama_file))) {
                    unlink(public_path('config_media/' . $data->nama_file));
                }

                $nama_file = $request->file('nama_file');
                $ext       = $nama_file->getClientOriginalExtension();
                $filename  = Str::random(25) . '.' . $ext;
                $nama_file->move(public_path('config_media/'), $filename);
            }

            $db = [
                'nama_file' => $filename,
            ];

            $data->update($db);
            $this->logEdit('Pengaturan Media', $data->id);

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
