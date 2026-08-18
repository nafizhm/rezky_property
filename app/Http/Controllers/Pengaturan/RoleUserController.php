<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleUser;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleUserController extends Controller
{
    use LogAktivitasTrait;

    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        $roles = Role::select('id', 'role')->get();

        return view('admin.pengaturan.role_user.index', compact('roles', 'permissions'));
    }

    public function getRoleUser(Request $request)
    {
        if (! $request->has('id_role') || empty($request->id_role)) {
            return response()->json(['data' => []]);
        }

        $permissions = $request->permissions;

        $hakAkses = RoleUser::with('menu')
            ->where('id_role', $request->id_role)
            ->get();

        $sorted = collect();

        $induk = $hakAkses->filter(fn ($row) => $row->menu && $row->menu->id_parent == 0)
            ->sortBy(fn ($row) => $row->menu->urutan ?? 0);

        foreach ($induk as $indukItem) {
            $sorted->push($indukItem);

            $anak = $hakAkses->filter(fn ($row) => $row->menu && $row->menu->id_parent == $indukItem->id_menu)
                ->sortBy(fn ($row) => $row->menu->urutan ?? 0);

            foreach ($anak as $anakItem) {
                $sorted->push($anakItem);
            }
        }

        return DataTables::of($sorted)
            ->addIndexColumn()
            ->addColumn('induk_menu', function ($row) {
                if (! $row->menu) {
                    return '-';
                }

                if ($row->menu->id_parent == 0) {
                    return 'Induk';
                }

                return Menu::find($row->menu->id_parent)?->title ?? 'Induk';
            })
            ->addColumn('title', fn ($row) => $row->menu->title ?? '-')
            ->addColumn('route_name', fn ($row) => $row->menu->route_name ?? '-')
            ->addColumn('lihat', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->lihat == 0) {
                    return '';
                }

                $checked = $row->lihat == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';

                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='lihat[{$row->id}]' $checked $disabled></div>";
            })
            ->addColumn('beranda', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->title === 'Beranda' || $row->menu->route_name === '#') {
                    return '';
                }

                $checked = $row->beranda == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';

                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='beranda[{$row->id}]' $checked $disabled></div>";
            })
            ->addColumn('tambah', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->tambah == 0) {
                    return '';
                }

                $checked = $row->tambah == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';

                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='tambah[{$row->id}]' $checked $disabled></div>";
            })
            ->addColumn('edit', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->edit == 0) {
                    return '';
                }

                $checked = $row->edit == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';

                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='edit[{$row->id}]' $checked $disabled></div>";
            })
            ->addColumn('hapus', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->hapus == 0) {
                    return '';
                }

                $checked = $row->hapus == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';

                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='hapus[{$row->id}]' $checked $disabled></div>";
            })
            ->rawColumns(['induk_menu', 'beranda', 'title', 'route_name', 'lihat', 'tambah', 'edit', 'hapus'])
            ->make(true);
    }

    public function updateRoleUser(Request $request)
    {
        $hakAksesData = $request->hak_akses_data;

        $allIds = collect($hakAksesData)->map(function ($item) {
            return array_keys($item);
        })->flatten()->unique();

        foreach ($allIds as $id) {
            $hakAkses = RoleUser::where('id', $id)->first();
            if ($hakAkses) {
                $hakAkses->lihat = $hakAksesData['lihat'][$id] ?? 0;
                $hakAkses->beranda = $hakAksesData['beranda'][$id] ?? 0;
                $hakAkses->tambah = $hakAksesData['tambah'][$id] ?? 0;
                $hakAkses->edit = $hakAksesData['edit'][$id] ?? 0;
                $hakAkses->hapus = $hakAksesData['hapus'][$id] ?? 0;
                $hakAkses->save();
            }
        }

        $this->logEdit('Role User', $request->id_role);

        return response()->json([
            'success' => true,
            'message' => 'Role User telah diperbarui.',
        ]);
    }
}
