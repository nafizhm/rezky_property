<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sppr', function (Blueprint $table) {
            $table->id();
            $table->integer('id_customer');
            $table->string('nama');
            $table->text('alamat');
            $table->string('nik', 20);
            $table->string('no_telp', 20);
            $table->bigInteger('luas_bangunan');
            $table->bigInteger('luas_tanah');
            $table->string('blok', 50);
            $table->string('no', 50);
            $table->bigInteger('harga_jual');
            $table->bigInteger('asumsi_plafon_kpr');
            $table->bigInteger('biaya_surat_surat');
            $table->bigInteger('peningkatan_mutu');
            $table->bigInteger('biaya_kelebihan_tanah')->nullable();
            $table->bigInteger('biaya_sudut')->nullable();
            $table->bigInteger('biaya_lain_lain')->nullable();
            $table->bigInteger('total_yang_harus_dibayar');
            $table->bigInteger('jumlah_booking_fee');
            $table->bigInteger('cicilan_per_bulan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sppr');
    }
};
