<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
    {
        Schema::create('detail_akad', function (Blueprint $table) {
            $table->id();
            $table->integer('id_akad');
            $table->integer('id_customer');
            $table->date('tgl_masuk');
            $table->string('status', 255);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_akad');
    }
};
