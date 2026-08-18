<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('customer', 'sbum')) {
            Schema::table('customer', function (Blueprint $table) {
                $table->bigInteger('sbum')->nullable()->default(0)->after('estimasi_plafon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customer', 'sbum')) {
            Schema::table('customer', function (Blueprint $table) {
                $table->dropColumn('sbum');
            });
        }
    }
};
