<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pembatalan', function (Blueprint $table) {
            $table->string('no_rekening')->nullable()->change();
            $table->string('nama_bank')->nullable()->change();
            $table->string('atas_nama')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pembatalan', function (Blueprint $table) {
            $table->string('no_rekening')->nullable(false)->change();
            $table->string('nama_bank')->nullable(false)->change();
            $table->string('atas_nama')->nullable(false)->change();
        });
    }
};
