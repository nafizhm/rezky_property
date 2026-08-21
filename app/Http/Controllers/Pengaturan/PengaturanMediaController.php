<?php
namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\PengaturanMedia;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PengaturanMediaController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        // Pengaturan media sekarang menjadi bagian dari halaman Pengaturan Aplikasi.
        // Gunakan hak akses menu induknya, bukan menu media lama yang disembunyikan.
        $permissions = HakAksesController::getUserPermissions('pengaturan-profil.index');
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
                    $btn     = '<div class="text-center">';

                    if ($permissions['edit']) {
                        $btn .= '<button type="button" class="btn btn-primary btn-sm edit-media-button"'
                            . ' data-id="' . e($row->id) . '"'
                            . ' data-jenis="' . e($row->jenis_data) . '"'
                            . ' data-file="' . e($row->nama_file) . '">Edit</button>';
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
        $newFilePath = null;
        $oldFilename = $data->nama_file;
        try {

            $mediaDirectory = public_path('config_media');
            File::ensureDirectoryExists($mediaDirectory);

            $nama_file = $request->file('nama_file');
            $ext       = strtolower($nama_file->getClientOriginalExtension());
            $filename  = Str::random(25) . '.' . $ext;
            $nama_file->move($mediaDirectory, $filename);
            $newFilePath = $mediaDirectory . DIRECTORY_SEPARATOR . $filename;

            $db = [
                'nama_file' => $filename,
            ];

            $data->update($db);
            $this->logEdit('Pengaturan Media', $data->id);

            DB::commit();

            // Hapus setelah database berhasil diperbarui dan hanya bila file lama
            // tidak sedang dipakai oleh konfigurasi media yang lain.
            if (! empty($oldFilename)
                && $oldFilename !== $filename
                && ! PengaturanMedia::where('id', '!=', $data->id)
                    ->where('nama_file', $oldFilename)
                    ->exists()) {
                File::delete($mediaDirectory . DIRECTORY_SEPARATOR . $oldFilename);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($newFilePath) {
                File::delete($newFilePath);
            }
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

}
