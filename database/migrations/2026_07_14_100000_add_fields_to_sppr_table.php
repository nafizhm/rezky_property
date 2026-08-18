<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sppr', function (Blueprint $table) {
            $table->string('agama', 50)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->text('promo')->nullable();
            $table->text('perubahan_posisi')->nullable();
            $table->string('keterangan_booking', 100)->nullable();
            $table->bigInteger('nominal_dp')->nullable();
            $table->string('keterangan_dp', 100)->nullable();
            $table->bigInteger('nominal_biaya_posisi_unit')->nullable();
            $table->string('keterangan_posisi_unit', 100)->nullable();
            $table->bigInteger('nominal_biaya_kpr')->nullable();
            $table->string('keterangan_kpr', 100)->nullable();
            $table->bigInteger('nominal_blokir_angsuran')->nullable();
            $table->string('keterangan_blokir_angsuran', 100)->nullable();
            $table->bigInteger('nominal_biaya_materai')->nullable();
            $table->string('keterangan_materai', 100)->nullable();
            $table->bigInteger('nominal_biaya_buka_tabungan')->nullable();
            $table->string('keterangan_tabungan', 100)->nullable();
            $table->string('keterangan_shm', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sppr', function (Blueprint $table) {
            $table->dropColumn([
                'agama', 'pekerjaan', 'promo', 'perubahan_posisi',
                'keterangan_booking', 'nominal_dp', 'keterangan_dp',
                'nominal_biaya_posisi_unit', 'keterangan_posisi_unit',
                'nominal_biaya_kpr', 'keterangan_kpr',
                'nominal_blokir_angsuran', 'keterangan_blokir_angsuran',
                'nominal_biaya_materai', 'keterangan_materai',
                'nominal_biaya_buka_tabungan', 'keterangan_tabungan',
                'keterangan_shm',
            ]);
        });
    }
};
