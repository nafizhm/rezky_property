<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $menuId = DB::table('menu')->where('route_name', 'beranda.index')->value('id');

        if (! $menuId) {
            $menuId = DB::table('menu')->insertGetId([
                'id_parent' => 0,
                'title' => 'Beranda',
                'route_name' => 'beranda.index',
                'icon' => 'fas fa-home',
                'urutan' => 0,
                'lihat' => 1,
                'tambah' => 0,
                'edit' => 0,
                'hapus' => 0,
            ]);
        } else {
            DB::table('menu')
                ->where('id', $menuId)
                ->update([
                    'id_parent' => 0,
                    'title' => 'Beranda',
                    'route_name' => 'beranda.index',
                    'icon' => 'fas fa-home',
                    'urutan' => 0,
                    'lihat' => 1,
                    'tambah' => 0,
                    'edit' => 0,
                    'hapus' => 0,
                ]);
        }

        $users = DB::table('users')->pluck('id');

        foreach ($users as $userId) {
            DB::table('hak_akses')->updateOrInsert(
                [
                    'id_user' => $userId,
                    'id_menu' => $menuId,
                ],
                [
                    'lihat' => 1,
                    'beranda' => 0,
                    'tambah' => 0,
                    'edit' => 0,
                    'hapus' => 0,
                ]
            );
        }

        $roles = DB::table('role')->pluck('id');

        foreach ($roles as $roleId) {
            DB::table('role_user')->updateOrInsert(
                [
                    'id_role' => $roleId,
                    'id_menu' => $menuId,
                ],
                [
                    'lihat' => 1,
                    'beranda' => 0,
                    'tambah' => 0,
                    'edit' => 0,
                    'hapus' => 0,
                ]
            );
        }
    }

    public function down(): void
    {
        $menu = DB::table('menu')->where('route_name', 'beranda.index')->first();

        if ($menu) {
            DB::table('hak_akses')->where('id_menu', $menu->id)->delete();
            DB::table('role_user')->where('id_menu', $menu->id)->delete();
            DB::table('menu')->where('id', $menu->id)->delete();
        }
    }
};
