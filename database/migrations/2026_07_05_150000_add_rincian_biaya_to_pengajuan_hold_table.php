<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_hold', function (Blueprint $table) {
            $table->dropColumn(['hrg_jual', 'biaya_surat', 'peningkatan_mutu']);
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_hold', function (Blueprint $table) {
            $table->integer('hrg_jual')->after('total_harga');
            $table->integer('biaya_surat')->after('hrg_jual');
            $table->integer('peningkatan_mutu')->after('biaya_surat');
        });
    }
};
