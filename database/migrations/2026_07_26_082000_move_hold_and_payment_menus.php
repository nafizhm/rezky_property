<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $transaksiId = DB::table('menu')->where('title', 'Transaksi')->value('id');
        $keuanganId = DB::table('menu')->where('title', 'Keuangan')->value('id');

        if ($transaksiId) {
            DB::table('menu')
                ->where('route_name', 'pengajuan-hold.index')
                ->update([
                    'id_parent' => $transaksiId,
                    'urutan' => 0,
                ]);
        }

        if ($keuanganId) {
            DB::table('menu')
                ->where('route_name', 'pembayaran.index')
                ->update([
                    'id_parent' => $keuanganId,
                    'urutan' => 0,
                ]);
        }
    }

    public function down(): void
    {
        DB::table('menu')
            ->where('route_name', 'pengajuan-hold.index')
            ->update([
                'id_parent' => 0,
                'urutan' => 4,
            ]);

        DB::table('menu')
            ->where('route_name', 'pembayaran.index')
            ->update([
                'id_parent' => 0,
                'urutan' => 5,
            ]);
    }
};
