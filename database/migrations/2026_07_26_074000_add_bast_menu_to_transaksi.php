<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $parent = DB::table('menu')->where('title', 'Transaksi')->first();

        if (! $parent) {
            return;
        }

        $menuId = DB::table('menu')->where('route_name', 'bast.index')->value('id');

        if (! $menuId) {
            $menuId = DB::table('menu')->insertGetId([
                'id_parent' => $parent->id,
                'title' => 'BAST',
                'route_name' => 'bast.index',
                'icon' => 'far fa-circle',
                'urutan' => 8,
                'lihat' => 1,
                'tambah' => 1,
                'edit' => 1,
                'hapus' => 1,
            ]);
        } else {
            DB::table('menu')
                ->where('id', $menuId)
                ->update([
                    'id_parent' => $parent->id,
                    'title' => 'BAST',
                    'route_name' => 'bast.index',
                    'urutan' => 8,
                    'lihat' => 1,
                    'tambah' => 1,
                    'edit' => 1,
                    'hapus' => 1,
                ]);
        }

        $users = DB::table('users')->pluck('id');

        foreach ($users as $userId) {
            $exists = DB::table('hak_akses')
                ->where('id_user', $userId)
                ->where('id_menu', $menuId)
                ->exists();

            if (! $exists) {
                DB::table('hak_akses')->insert([
                    'id_user' => $userId,
                    'id_menu' => $menuId,
                    'lihat' => 1,
                    'beranda' => 0,
                    'tambah' => 1,
                    'edit' => 1,
                    'hapus' => 1,
                ]);
            }
        }
    }

    public function down(): void
    {
        $menu = DB::table('menu')->where('route_name', 'bast.index')->first();

        if ($menu) {
            DB::table('hak_akses')->where('id_menu', $menu->id)->delete();
            DB::table('menu')->where('id', $menu->id)->delete();
        }
    }
};
