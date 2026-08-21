<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\HakAkses;
use App\Models\Menu;
use App\Models\User;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class HakAksesController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = $this->getUserPermissions();

        $users = User::select('id', 'username')
            ->when(Auth::user()->username != 'dev', function ($query) {
                $query->where('username', '!=', 'dev');
            })
            ->get();

        return view('admin.pengaturan.hak_akses.index', compact('users', 'permissions'));
    }

    public static function getUserPermissions(?string $routeName = null)
    {
        $routeName = $routeName ?? request()->route()?->getName();
        $userId    = Auth::id();

        $menu = Menu::where('route_name', $routeName)->first();

        if ($menu) {
            $hakAkses = HakAkses::where('id_user', $userId)
                ->where('id_menu', $menu->id)
                ->first();

            return $hakAkses ? [
                'tambah' => $hakAkses->tambah,
                'edit'   => $hakAkses->edit,
                'hapus'  => $hakAkses->hapus,
            ] : [
                'tambah' => 0,
                'edit'   => 0,
                'hapus'  => 0,
            ];
        }

        return [
            'tambah' => 0,
            'edit'   => 0,
            'hapus'  => 0,
        ];
    }

    private function generateHakAkses($id_user)
    {
        $user = User::find($id_user);
        if (! $user) {
            return;
        }

        $menus         = Menu::all();
        $existingMenus = HakAkses::where('id_user', $user->id)->pluck('id_menu')->toArray();
        $missingMenus  = $menus->whereNotIn('id', $existingMenus);

        if ($missingMenus->count() > 0) {
            $addedMenus = [];

            foreach ($missingMenus as $menu) {
                $akses = [
                    'id_user' => $user->id,
                    'id_menu' => $menu->id,
                    'lihat'   => ($user->role == 4 || $user->role == 2) ? $menu->lihat : 0,
                    'tambah'  => ($user->role == 4 || $user->role == 2) ? $menu->tambah : 0,
                    'edit'    => ($user->role == 4 || $user->role == 2) ? $menu->edit : 0,
                    'hapus'   => ($user->role == 4 || $user->role == 2) ? $menu->hapus : 0,
                ];

                HakAkses::create($akses);
                $addedMenus[] = $menu->title ?? $menu->id;
            }

            Log::info("Hak akses ditambahkan untuk user {$user->username}:", $addedMenus);
        }
    }

    public function getHakAkses(Request $request)
    {
        if (! $request->has('id_user') || empty($request->id_user)) {
            return response()->json(['data' => []]);
        }

        $this->generateHakAkses($request->id_user);

        $permissions = $request->permissions;

        $hakAkses = HakAkses::with('menu')
            ->where('id_user', $request->id_user)
            ->get();

        $sorted = collect();

        $induk = $hakAkses->filter(fn($row) => $row->menu && $row->menu->id_parent == 0)
            ->sortBy(fn($row) => $row->menu->urutan ?? 0);

        foreach ($induk as $indukItem) {
            $sorted->push($indukItem);

            $anak = $hakAkses->filter(fn($row) => $row->menu && $row->menu->id_parent == $indukItem->id_menu)
                ->sortBy(fn($row) => $row->menu->urutan ?? 0);

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
            ->addColumn('title', fn($row) => $row->menu->title ?? '-')
            ->addColumn('route_name', fn($row) => $row->menu->route_name ?? '-')
            ->addColumn('lihat', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->lihat == 0) {
                    return '';
                }

                $checked  = $row->lihat == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';
                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='lihat[{$row->id}]' $checked $disabled></div>";
            })
            ->addColumn('tambah', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->tambah == 0) {
                    return '';
                }

                $checked  = $row->tambah == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';
                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='tambah[{$row->id}]' $checked $disabled></div>";
            })
            ->addColumn('edit', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->edit == 0) {
                    return '';
                }

                $checked  = $row->edit == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';
                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='edit[{$row->id}]' $checked $disabled></div>";
            })
            ->addColumn('hapus', function ($row) use ($permissions) {
                if (! $row->menu || $row->menu->hapus == 0) {
                    return '';
                }

                $checked  = $row->hapus == 1 ? 'checked' : '';
                $disabled = ($permissions['edit'] ?? 1) == 0 ? 'disabled' : '';
                return "<div class='text-center'><input type='checkbox' class='form-check-input' name='hapus[{$row->id}]' $checked $disabled></div>";
            })
            ->rawColumns(['induk_menu', 'title', 'route_name', 'lihat', 'tambah', 'edit', 'hapus'])
            ->make(true);
    }

    public function updateHakAkses(Request $request)
    {
        $hakAksesData = $request->hak_akses_data;

        $allIds = collect($hakAksesData)->map(function ($item) {
            return array_keys($item);
        })->flatten()->unique();

        foreach ($allIds as $id) {
                $hakAkses = HakAkses::where('id', $id)->first();
            if ($hakAkses) {
                $hakAkses->lihat   = $hakAksesData['lihat'][$id] ?? 0;
                $hakAkses->tambah  = $hakAksesData['tambah'][$id] ?? 0;
                $hakAkses->edit    = $hakAksesData['edit'][$id] ?? 0;
                $hakAkses->hapus   = $hakAksesData['hapus'][$id] ?? 0;
                $hakAkses->save();
            }
        }

        $userId = Auth::id();

        $hakAkses = HakAkses::where('id_user', $userId)
            ->where('lihat', 1)
            ->get();
        
        $this->logEdit('Hak Akses', $userId);

        $allowedMenuIds = $hakAkses->pluck('id_menu')->toArray();

        $getmenus = Menu::where('id_parent', 0)
            ->whereIn('id', $allowedMenuIds)
            ->orderBy('urutan')
            ->with([
                'children' => function ($query) use ($allowedMenuIds) {
                    $query->whereIn('id', $allowedMenuIds);
                },
            ])
            ->get()
            ->filter(function ($menu) {
                $menu->setRelation('children', $menu->children->filter(fn ($child) => Route::has($child->route_name))->values());

                return $menu->children->isNotEmpty() || Route::has($menu->route_name);
            })
            ->values();

        session(['getmenus' => $getmenus]);

        return response()->json([
            'success' => true,
            'message' => 'Hak Akses telah diperbarui.',
        ]);
    }
}
