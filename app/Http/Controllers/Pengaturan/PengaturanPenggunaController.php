<?php
namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\HakAkses;
use App\Models\MarketingOffline;
use App\Models\PengaturanPengguna;
use App\Models\Role;
use App\Models\RoleUser;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PengaturanPenggunaController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = PengaturanPengguna::with('role')
                ->when(Auth::id() != 1, function ($q) {
                    $q->where('id', '!=', 1);
                })
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('role', function ($row) {
                    if (! $row->role) {
                        return '<span class="badge bg-secondary text-white">Tidak Diketahui</span>';
                    }

                    switch (strtolower($row->role->role)) {
                        case 'marketing':
                            return '<span class="badge bg-info text-white">' . e($row->role->role) . '</span>';
                        case 'admin':
                            return '<span class="badge bg-success text-white">' . e($row->role->role) . '</span>';
                        case 'manager':
                            return '<span class="badge bg-warning text-white">' . e($row->role->role) . '</span>';
                        case 'super admin':
                            return '<span class="badge bg-danger text-white">' . e($row->role->role) . '</span>';
                        default:
                            return '<span class="badge bg-secondary text-white">' . e($row->role->role) . '</span>';
                    }
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('pengaturan-pengguna.edit', $row->id);
                    $deleteUrl = route('pengaturan-pengguna.destroy', $row->id);

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

                ->rawColumns(['role', 'action'])
                ->make(true);
        }

        $roles     = Role::select('id', 'role')->get();
        $marketing = MarketingOffline::select('id', 'nama_marketing')->get();

        $auth = Auth::user();

        return view('admin.pengaturan.pengguna.index', compact('roles', 'permissions', 'auth', 'marketing'));
    }

    public function edit($id)
    {
        $list = PengaturanPengguna::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'surname'      => 'required',
            'username'     => 'required|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'status'       => 'required|in:AKTIF,BLOKIR',
            'role'         => 'required',
            'id_marketing' => 'required_if:role,2',
        ], [
            'surname.required'         => 'Nama lengkap wajib diisi.',
            'username.required'        => 'Username wajib diisi.',
            'username.unique'          => 'Username sudah digunakan.',
            'email.required'           => 'Email wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'email.unique'             => 'Email sudah digunakan.',
            'password.required'        => 'Password wajib diisi.',
            'password.min'             => 'Password minimal 6 karakter.',
            'status.required'          => 'Status wajib dipilih.',
            'status.in'                => 'Status tidak valid.',
            'role.required'            => 'Role wajib dipilih.',
            'id_marketing.required_if' => 'Marketing wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $db = [
                'surname'      => $request->surname,
                'username'     => $request->username,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'status'       => $request->status,
                'id_role'      => $request->role,
                'id_marketing' => $request->id_marketing ?? 0,
            ];

            $user = PengaturanPengguna::create($db);
            $this->logCreate('Pengaturan Pengguna', $user->id);

            $roleMenus    = RoleUser::where('id_role', $request->role)->get();
            $dataHakAkses = $roleMenus->map(function ($roleMenu) use ($user) {
                return [
                    'id_user' => $user->id,
                    'id_menu' => $roleMenu->id_menu,
                    'lihat'   => $roleMenu->lihat,
                    'beranda' => $roleMenu->beranda,
                    'tambah'  => $roleMenu->tambah,
                    'edit'    => $roleMenu->edit,
                    'hapus'   => $roleMenu->hapus,
                ];
            })->toArray();

            HakAkses::insert($dataHakAkses);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = PengaturanPengguna::findOrFail($id);

        $request->validate([
            'surname'      => 'required',
            'username'     => 'required|unique:users,username,' . $data->id . ',id',
            'email'        => 'required|unique:users,email,' . $data->id . ',id',
            'password'     => 'nullable|string|min:6',
            'status'       => 'required|in:AKTIF,BLOKIR',
            'role'         => 'required',
            'id_marketing' => 'required_if:role,2',
        ], [
            'surname.required'         => 'Nama lengkap wajib diisi.',
            'username.required'        => 'Username wajib diisi.',
            'username.unique'          => 'Username sudah digunakan.',
            'email.required'           => 'Email wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'email.unique'             => 'Email sudah digunakan.',
            'password.min'             => 'Password minimal 6 karakter.',
            'status.required'          => 'Status wajib dipilih.',
            'status.in'                => 'Status tidak valid.',
            'role.required'            => 'Role wajib dipilih.',
            'id_marketing.required_if' => 'Marketing wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $db = [
                'surname'      => $request->surname,
                'username'     => $request->username,
                'email'        => $request->email,
                'status'       => $request->status,
                'id_role'      => $request->role,
                'id_marketing' => $request->id_marketing ?? 0,
            ];

            if ($request->filled('password')) {
                $db['password'] = Hash::make($request->password);
            }

            $roleBerubah = $data->id_role != $request->role;
            $data->update($db);
            $this->logEdit('Pengaturan Pengguna', $data->id);

            if ($roleBerubah) {
                HakAkses::where('id_user', $data->id)->delete();

                $roleMenus    = RoleUser::where('id_role', $request->role)->get();
                $dataHakAkses = $roleMenus->map(function ($roleMenu) use ($data) {
                    return [
                        'id_user' => $data->id,
                        'id_menu' => $roleMenu->id_menu,
                        'lihat'   => $roleMenu->lihat,
                        'beranda' => $roleMenu->beranda,
                        'tambah'  => $roleMenu->tambah,
                        'edit'    => $roleMenu->edit,
                        'hapus'   => $roleMenu->hapus,
                    ];
                })->toArray();

                HakAkses::insert($dataHakAkses);
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

    public function updateUser(Request $request, $id)
    {
        $data = PengaturanPengguna::findOrFail($id);

        $request->validate([
            'surname'  => 'required',
            'username' => 'required|unique:users,username,' . $data->id . ',id',
            'email'    => 'required|unique:users,email,' . $data->id . ',id',
            'password' => 'nullable|min:5',
        ], [
            'surname.required'  => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan.',
            'password.min'      => 'Password minimal 5 karakter.',
        ]);

        DB::beginTransaction();
        try {
            $db = [
                'surname'  => $request->surname,
                'username' => $request->username,
                'email'    => $request->email,
            ];

            if ($request->filled('password')) {
                $db['password'] = Hash::make($request->password);
            }

            $data->update($db);
            $this->logEdit('Pengaturan Pengguna', $data->id);

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
        $data = PengaturanPengguna::findOrFail($id);
        HakAkses::where('id_user', $data->id)->delete();

        $this->logDelete('Pengaturan Pengguna', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
