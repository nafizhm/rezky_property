<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('pembatalan', function (Blueprint $table) {
            $table->string('no_kwitansi', 50)->after('id_batal');

            $table->string('jumlah_bayar', 255)->after('no_rekening');

            $table->string('metode_bayar', 100)->after('jumlah_bayar');
            $table->string('lampiran_bukti', 255)->after('metode_bayar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembatalan', function (Blueprint $table) {
            $table->dropColumn(['no_kwitansi', 'jumlah_bayar', 'metode_bayar', 'lampiran_bukti']);
        });
    }

};
