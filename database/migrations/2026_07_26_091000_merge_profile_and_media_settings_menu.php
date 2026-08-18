<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $profileMenu = DB::table('menu')
            ->where('route_name', 'pengaturan-profil.index')
            ->first();

        $mediaMenu = DB::table('menu')
            ->where('route_name', 'pengaturan-media.index')
            ->first();

        if ($profileMenu) {
            DB::table('menu')
                ->where('id', $profileMenu->id)
                ->update([
                    'title' => 'Pengaturan Aplikasi',
                    'urutan' => 1,
                    'lihat' => 1,
                    'tambah' => 1,
                    'edit' => 1,
                    'hapus' => 1,
                ]);
        }

        if (! $profileMenu || ! $mediaMenu) {
            return;
        }

        DB::table('hak_akses')
            ->where('id_menu', $mediaMenu->id)
            ->update(['lihat' => 0]);

        DB::table('role_user')
            ->where('id_menu', $mediaMenu->id)
            ->update(['lihat' => 0]);

        DB::table('menu')
            ->where('id', $mediaMenu->id)
            ->update([
                'title' => 'Pengaturan Media',
                'urutan' => 99,
                'lihat' => 0,
            ]);

        DB::table('menu')
            ->where('id_parent', $profileMenu->id_parent)
            ->where('id', '!=', $profileMenu->id)
            ->where('id', '!=', $mediaMenu->id)
            ->where('urutan', '>', $mediaMenu->urutan)
            ->decrement('urutan');
    }

    public function down(): void
    {
        //
    }
};
