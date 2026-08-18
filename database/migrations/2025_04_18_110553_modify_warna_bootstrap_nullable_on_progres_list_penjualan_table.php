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
        Schema::table('progres_list_penjualan', function (Blueprint $table) {
            $table->string('warna_bootstrap')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('progres_list_penjualan', function (Blueprint $table) {
            $table->string('warna_bootstrap')->nullable(false)->change(); // rollback: jadi NOT NULL lagi
        });
    }
};
