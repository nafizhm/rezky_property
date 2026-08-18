<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kavling_peta', function (Blueprint $table) {
            $table->json('rincian_biaya')->nullable()->after('hrg_meter');
        });
    }

    public function down(): void
    {
        Schema::table('kavling_peta', function (Blueprint $table) {
            $table->dropColumn('rincian_biaya');
        });
    }
};
