<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $transaksi = DB::table('menu')
            ->where('title', 'Transaksi')
            ->where('id_parent', 0)
            ->first();

        if (! $transaksi) {
            return;
        }

        $mutasiMenuId = DB::table('menu')
            ->where('title', 'Mutasi Transaksi')
            ->where('id_parent', 0)
            ->value('id');

        if (! $mutasiMenuId) {
            DB::table('menu')
                ->where('id_parent', 0)
                ->where('urutan', '>', $transaksi->urutan)
                ->increment('urutan');

            $mutasiMenuId = DB::table('menu')->insertGetId([
                'id_parent' => 0,
                'title' => 'Mutasi Transaksi',
                'route_name' => '#',
                'icon' => 'fas fa-exchange-alt',
                'urutan' => $transaksi->urutan + 1,
                'lihat' => 1,
                'tambah' => 1,
                'edit' => 1,
                'hapus' => 1,
            ]);
        } else {
            DB::table('menu')
                ->where('id', $mutasiMenuId)
                ->update([
                    'id_parent' => 0,
                    'title' => 'Mutasi Transaksi',
                    'route_name' => '#',
                    'icon' => 'fas fa-exchange-alt',
                    'urutan' => $transaksi->urutan + 1,
                    'lihat' => 1,
                    'tambah' => 1,
                    'edit' => 1,
                    'hapus' => 1,
                ]);
        }

        $this->syncAccess($mutasiMenuId);

        $transaksiOrder = [
            'pengajuan-hold.index' => 1,
            'sppr.index' => 2,
            'wawancara.index' => 3,
            'acc-bank.index' => 4,
            'ppjb.index' => 5,
            'akad.index' => 6,
            'bast.index' => 7,
        ];

        foreach ($transaksiOrder as $routeName => $urutan) {
            DB::table('menu')
                ->where('route_name', $routeName)
                ->update([
                    'id_parent' => $transaksi->id,
                    'urutan' => $urutan,
                ]);
        }

        $mutasiOrder = [
            'pindah-unit.index' => 1,
            'ganti-nama.index' => 2,
            'pembelian-cancel.index' => 3,
        ];

        foreach ($mutasiOrder as $routeName => $urutan) {
            DB::table('menu')
                ->where('route_name', $routeName)
                ->update([
                    'id_parent' => $mutasiMenuId,
                    'urutan' => $urutan,
                ]);
        }
    }

    private function syncAccess(int $menuId): void
    {
        foreach (DB::table('users')->pluck('id') as $userId) {
            DB::table('hak_akses')->updateOrInsert(
                [
                    'id_user' => $userId,
                    'id_menu' => $menuId,
                ],
                [
                    'lihat' => 1,
                    'beranda' => 0,
                    'tambah' => 1,
                    'edit' => 1,
                    'hapus' => 1,
                ]
            );
        }

        foreach (DB::table('role')->pluck('id') as $roleId) {
            DB::table('role_user')->updateOrInsert(
                [
                    'id_role' => $roleId,
                    'id_menu' => $menuId,
                ],
                [
                    'lihat' => 1,
                    'beranda' => 0,
                    'tambah' => 1,
                    'edit' => 1,
                    'hapus' => 1,
                ]
            );
        }
    }

    public function down(): void
    {
        //
    }
};
