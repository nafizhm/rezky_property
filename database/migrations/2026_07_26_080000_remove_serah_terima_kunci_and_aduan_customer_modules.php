<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $menuIds = DB::table('menu')
            ->whereIn('route_name', [
                'aduan-customer.index',
                'serah-terima-kunci.index',
            ])
            ->orWhereIn('title', [
                'Aduan Customer',
                'Serah Terima Kunci',
            ])
            ->pluck('id');

        if ($menuIds->isNotEmpty()) {
            DB::table('hak_akses')->whereIn('id_menu', $menuIds)->delete();
            DB::table('role_user')->whereIn('id_menu', $menuIds)->delete();
            DB::table('menu')->whereIn('id', $menuIds)->delete();
        }

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('file_proses_aduan');
        Schema::dropIfExists('file_aduan');
        Schema::dropIfExists('aduan_proses');
        Schema::dropIfExists('aduan');
        Schema::dropIfExists('serah_terima_kunci');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        //
    }
};
