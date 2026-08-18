<?php
namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Customer;
use App\Models\PersyaratanLegal;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BerkasPengajuanController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = PersyaratanLegal::with('customer')->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_customer', function ($row) {
                    return $row->customer?->nama_lengkap ?? '';
                })
                ->addColumn('IPH', function ($row) {
                    return $row->IPH == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })
                ->addColumn('SHGB', function ($row) {
                    return $row->SHGB == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })
                ->addColumn('SSP', function ($row) {
                    return $row->SSP == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })
                ->addColumn('BPHTB', function ($row) {
                    return $row->BPHTB == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })
                ->addColumn('SIKUMBANG', function ($row) {
                    return $row->SIKUMBANG == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })
                ->addColumn('DAFTAR_SIKASEP', function ($row) {
                    return $row->DAFTAR_SIKASEP == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })
                ->addColumn('FOTO_SIKASEP', function ($row) {
                    return $row->FOTO_SIKASEP == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })
                ->addColumn('TRILOGI', function ($row) {
                    return $row->TRILOGI == 1
                        ? '<i class="fas fa-check-circle text-success"></i>'
                        : '<i class="fas fa-times-circle text-danger"></i>';
                })

                ->addColumn('action', function ($row) use ($permissions): string {
                    $editUrl = route('pengajuan-berkas.edit', $row->id);
                    $hasCust = $row->customer ? true : false;

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button ' . ($hasCust ? '' : 'disabled') . '" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';

                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['nama_customer', 'IPH', 'SHGB', 'SSP', 'BPHTB', 'SIKUMBANG', 'DAFTAR_SIKASEP', 'FOTO_SIKASEP', 'TRILOGI', 'action'])
                ->make(true);
        }

        return view('admin.legal.pengajuan_berkas.index', compact('permissions'));
    }

    public function edit($id)
    {
        $list = PersyaratanLegal::with('customer')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = PersyaratanLegal::findOrFail($id);

        $request->validate([
            'IPH'                => 'required',
            'SHGB'               => 'required',
            'SSP'                => 'required',
            'BPHTB'              => 'required',
            'SIKUMBANG'          => 'required',
            'DAFTAR_SIKASEP'     => 'required',
            'FOTO_SIKASEP'       => 'required',
            'TRILOGI'            => 'required',
            'catatan_kekurangan' => 'nullable',
            'percakapan_wa'      => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ], [
            'IPH.required'            => 'IPH wajib dipilih!',
            'SHGB.required'           => 'SHGB wajib dipilih!',
            'SSP.required'            => 'SSP wajib dipilih!',
            'BPHTB.required'          => 'BPHTB wajib dipilih!',
            'SIKUMBANG.required'      => 'SIKUMBANG wajib dipilih!',
            'DAFTAR_SIKASEP.required' => 'DAFTAR SIKASEP wajib dipilih!',
            'FOTO_SIKASEP.required'   => 'FOTO SIKASEP wajib dipilih!',
            'TRILOGI.required'        => 'TRILOGI wajib dipilih!',
            'percakapan_wa.file'      => 'File percakapan WA harus berupa file!',
            'percakapan_wa.mimes'     => 'File percakapan WA harus berupa gambar dengan format jpeg, png, atau jpg!',
            'percakapan_wa.max'       => 'Ukuran file percakapan WA maksimal 2MB!',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('percakapan_wa')) {
                if (! empty($data->percakapan_wa) && file_exists(public_path('assets/legal/pengajuan_berkas/percakapan_wa/' . $data->percakapan_wa))) {
                    unlink(public_path('assets/legal/pengajuan_berkas/percakapan_wa/' . $data->percakapan_wa));
                }

                $foto             = $request->file('percakapan_wa');
                $ext              = $foto->getClientOriginalExtension();
                $percakapanwaName = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/legal/pengajuan_berkas/percakapan_wa/'), $percakapanwaName);
            }

            $data->update([
                'IPH'                => $request->IPH,
                'SHGB'               => $request->SHGB,
                'SSP'                => $request->SSP,
                'BPHTB'              => $request->BPHTB,
                'SIKUMBANG'          => $request->SIKUMBANG,
                'DAFTAR_SIKASEP'     => $request->DAFTAR_SIKASEP,
                'FOTO_SIKASEP'       => $request->FOTO_SIKASEP,
                'TRILOGI'            => $request->TRILOGI,
                'catatan_kekurangan' => $request->catatan_kekurangan ?? '',
                'percakapan_wa'      => isset($percakapanwaName) ? $percakapanwaName : $data->percakapan_wa,
            ]);

            $this->logEdit('Berkas Pengajuan', $data->id);

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
