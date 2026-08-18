<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Perusahaan;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PerusahaanController extends Controller
{
    use LogAktivitasTrait;
    public function index()
    {
        $permissions = HakAksesController::getUserPermissions();

        if (request()->ajax()) {
            $data = Perusahaan::orderBy('id', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('perusahaan.edit', $row->id);
                    $deleteUrl = route('perusahaan.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . e($row->id) . '"
                                data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-button btn btn-danger btn-sm mx-1">
                        Hapus
                    </button>
                    </form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.master.perusahaan.index', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan'       => 'required|string|max:100',
            'alamat_perusahaan'     => 'required|string|max:200',
            'telp_perusahaan'       => 'required|string|max:35',
            'kota_penandatangan'    => 'required|string|max:35',
            'nama_penandatangan'    => 'required|string|max:50',
            'jabatan_penandatangan' => 'required|string|max:50',
            'nama_mengetahui'       => 'required|string|max:50',
            'bg_kwitansi'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kop_surat'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'nama_perusahaan.required'       => 'Nama perusahaan wajib diisi.',
            'alamat_perusahaan.required'     => 'Alamat wajib diisi.',
            'telp_perusahaan.required'       => 'Telepon wajib diisi.',
            'kota_penandatangan.required'    => 'Kota penandatangan wajib diisi.',
            'nama_penandatangan.required'    => 'Nama penandatangan wajib diisi.',
            'jabatan_penandatangan.required' => 'Jabatan penandatangan wajib diisi.',
            'nama_mengetahui.required'       => 'Nama mengetahui wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'nama_perusahaan'       => $request->nama_perusahaan,
                'alamat_perusahaan'     => $request->alamat_perusahaan,
                'telp_perusahaan'       => $request->telp_perusahaan,
                'kota_penandatangan'    => $request->kota_penandatangan,
                'nama_penandatangan'    => $request->nama_penandatangan,
                'jabatan_penandatangan' => $request->jabatan_penandatangan,
                'nama_mengetahui'       => $request->nama_mengetahui,
                'bg_kwitansi'           => $request->bg_kwitansi ?? '',
                'kop_surat'             => $request->kop_surat ?? '',
            ];

            if ($request->hasFile('bg_kwitansi')) {
                $file     = $request->file('bg_kwitansi');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/lokasi_perumahan/bg_kwitansi/'), $filename);
                $data['bg_kwitansi'] = $filename;
            }

            if ($request->hasFile('kop_surat')) {
                $file     = $request->file('kop_surat');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/lokasi_perumahan/kop_surat/'), $filename);
                $data['kop_surat'] = $filename;
            }

            $p = Perusahaan::create($data);
            $this->logCreate('Perusahaan', $p->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data perusahaan berhasil disimpan.',
                'data'    => $data,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data perusahaan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Perusahaan::find($id);

        if (! $data) {
            return response()->json([
                'success' => false,
                'message' => 'Data Perumahaan tidak ditemukan.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Perumahaan berhasil ditemukan.',
            'data'    => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_perusahaan'       => 'required|string|max:100',
            'alamat_perusahaan'     => 'required|string|max:200',
            'telp_perusahaan'       => 'required|string|max:35',
            'kota_penandatangan'    => 'required|string|max:35',
            'nama_penandatangan'    => 'required|string|max:50',
            'jabatan_penandatangan' => 'required|string|max:50',
            'nama_mengetahui'       => 'required|string|max:50',
            'bg_kwitansi'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kop_surat'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $perusahaan = Perusahaan::find($id);
            if (! $perusahaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data perusahaan tidak ditemukan.',
                ], 404);
            }

            $data = [
                'nama_perusahaan'       => $request->nama_perusahaan,
                'alamat_perusahaan'     => $request->alamat_perusahaan,
                'telp_perusahaan'       => $request->telp_perusahaan,
                'kota_penandatangan'    => $request->kota_penandatangan,
                'nama_penandatangan'    => $request->nama_penandatangan,
                'jabatan_penandatangan' => $request->jabatan_penandatangan,
                'nama_mengetahui'       => $request->nama_mengetahui,
                'bg_kwitansi'           => $perusahaan->bg_kwitansi,
                'kop_surat'             => $perusahaan->kop_surat,
            ];

            // Handle Kwitansi
            if ($request->hasFile('bg_kwitansi')) {
                $file     = $request->file('bg_kwitansi');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/lokasi_perumahan/bg_kwitansi/'), $filename);

                // hapus file lama kalau ada
                if ($perusahaan->bg_kwitansi && file_exists(public_path('assets/lokasi_perumahan/bg_kwitansi/' . $perusahaan->bg_kwitansi))) {
                    unlink(public_path('assets/lokasi_perumahan/bg_kwitansi/' . $perusahaan->bg_kwitansi));
                }

                $data['bg_kwitansi'] = $filename;
            }

            // Handle Kop Surat
            if ($request->hasFile('kop_surat')) {
                $file     = $request->file('kop_surat');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/lokasi_perumahan/kop_surat/'), $filename);

                // hapus file lama kalau ada
                if ($perusahaan->kop_surat && file_exists(public_path('assets/lokasi_perumahan/kop_surat/' . $perusahaan->kop_surat))) {
                    unlink(public_path('assets/lokasi_perumahan/kop_surat/' . $perusahaan->kop_surat));
                }

                $data['kop_surat'] = $filename;
            }

            $perusahaan->update($data);
            $this->logEdit('Perusahaan', $perusahaan->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data perusahaan berhasil diperbarui.',
                'data'    => $perusahaan,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data perusahaan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $perusahaan = Perusahaan::find($id);
            if (! $perusahaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data perusahaan tidak ditemukan.',
                ], 404);
            }

            if ($perusahaan->bg_kwitansi && file_exists(public_path('assets/lokasi_perumahan/bg_kwitansi/' . $perusahaan->bg_kwitansi))) {
                unlink(public_path('assets/lokasi_perumahan/bg_kwitansi/' . $perusahaan->bg_kwitansi));
            }

            if ($perusahaan->kop_surat && file_exists(public_path('assets/lokasi_perumahan/kop_surat/' . $perusahaan->kop_surat))) {
                unlink(public_path('assets/lokasi_perumahan/kop_surat/' . $perusahaan->kop_surat));
            }

            $this->logDelete('Perusahaan', $perusahaan->id);
            $perusahaan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data perusahaan berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data perusahaan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
