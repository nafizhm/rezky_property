<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPindahRumahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pindah_rumah', function (Blueprint $table) {
            $table->string('no_kwitansi', 50)->after('id_pindah');

            $table->string('jumlah_bayar', 255)->after('biaya_admin');

            $table->string('metode_bayar', 100);
            $table->string('lampiran_bukti', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pindah_rumah', function (Blueprint $table) {
            $table->dropColumn(['no_kwitansi', 'jumlah_bayar', 'metode_bayar', 'lampiran_bukti']);
        });
    }
}
