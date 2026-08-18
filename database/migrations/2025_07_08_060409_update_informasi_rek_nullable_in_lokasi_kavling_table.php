<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInformasiRekNullableInLokasiKavlingTable extends Migration
{
    public function up()
    {
        Schema::table('lokasi_kavling', function (Blueprint $table) {
            $table->string('informasi_rek')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('lokasi_kavling', function (Blueprint $table) {
            $table->string('informasi_rek')->nullable(false)->change();
        });
    }
}
