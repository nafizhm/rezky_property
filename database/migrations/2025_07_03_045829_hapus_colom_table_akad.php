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
        Schema::table('akad', function (Blueprint $table) {
            $table->dropColumn('id_customer');
            $table->dropColumn('id_kavling');
        });
    }

    public function down()
    {
        Schema::table('akad', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->unsignedBigInteger('id_kavling')->nullable();
        });
    }
};
