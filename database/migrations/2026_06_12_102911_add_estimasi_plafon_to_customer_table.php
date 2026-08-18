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
        if (! Schema::hasColumn('customer', 'estimasi_plafon')) {
            Schema::table('customer', function (Blueprint $table) {
                $table->bigInteger('estimasi_plafon')->nullable()->default(0)->after('total_harga');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customer', 'estimasi_plafon')) {
            Schema::table('customer', function (Blueprint $table) {
                $table->dropColumn('estimasi_plafon');
            });
        }
    }
};
