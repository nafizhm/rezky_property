<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_biaya', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unik', 50)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('wajib')->default(false);
            $table->boolean('aktif')->default(true);
            $table->string('satuan')->default('Rp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_biaya');
    }
};
