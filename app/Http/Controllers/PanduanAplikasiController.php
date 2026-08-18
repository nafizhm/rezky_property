<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanduanAplikasiController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.panduan_aplikasi.index', compact('roles'));
    }

    public function getMenuByRole(Request $request)
    {
        $menus = DB::table('menu_panduan_aplikasi as m')
            ->join('hak_akses_panduan_apk as h', 'h.id_menu_panduan', '=', 'm.id')
            ->where('h.id_role', $request->id_role)
            ->where('h.akses', 1)
            ->orderBy('m.id')
            ->select('m.id', 'm.judul', 'm.link_yt')
            ->get();

        return response()->json($menus);
    }
}
