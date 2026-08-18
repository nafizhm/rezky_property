<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $keuanganMenu = DB::table('menu')->where('title', 'Keuangan')->first();

        if (! $keuanganMenu) {
            return;
        }

        $existingMenu = DB::table('menu')->where('route_name', 'keuangan-retensi.index')->first();

        if ($existingMenu) {
            return;
        }

        $maxUrutan = DB::table('menu')
            ->where('id_parent', $keuanganMenu->id)
            ->max('urutan');

        $menuId = DB::table('menu')->insertGetId([
            'id_parent' => $keuanganMenu->id,
            'title' => 'Retensi',
            'route_name' => 'keuangan-retensi.index',
            'icon' => 'far fa-circle',
            'urutan' => ($maxUrutan ?? 0) + 1,
            'lihat' => 1,
            'tambah' => 0,
            'edit' => 0,
            'hapus' => 0,
        ]);

        $roleColumn = Schema::hasColumn('users', 'id_role')
            ? 'id_role'
            : (Schema::hasColumn('users', 'role') ? 'role' : null);

        $users = $roleColumn
            ? DB::table('users')->select('id', $roleColumn)->get()
            : DB::table('users')->select('id')->get();
        $hakAkses = [];

        foreach ($users as $user) {
            $roleValue = $roleColumn ? ($user->{$roleColumn} ?? null) : null;
            $isDefaultAllowed = in_array((int) $roleValue, [2, 4], true);

            $hakAkses[] = [
                'id_user' => $user->id,
                'id_menu' => $menuId,
                'lihat' => $isDefaultAllowed ? 1 : 0,
                'tambah' => 0,
                'edit' => 0,
                'hapus' => 0,
            ];
        }

        if (! empty($hakAkses)) {
            DB::table('hak_akses')->insert($hakAkses);
        }
    }

    public function down(): void
    {
        $menu = DB::table('menu')->where('route_name', 'keuangan-retensi.index')->first();

        if ($menu) {
            DB::table('hak_akses')->where('id_menu', $menu->id)->delete();
            DB::table('menu')->where('id', $menu->id)->delete();
        }
    }
};
