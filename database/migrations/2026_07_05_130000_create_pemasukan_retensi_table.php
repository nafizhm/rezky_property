<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemasukan_retensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemasukan');
            $table->unsignedBigInteger('id_retensi');
            $table->bigInteger('nominal')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemasukan_retensi');
    }
};
