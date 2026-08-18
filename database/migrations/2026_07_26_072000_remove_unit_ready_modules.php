<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $routeNames = [
            'unit-ready.index',
            'siteplan-unit-ready.index',
        ];

        $menuIds = DB::table('menu')
            ->whereIn('route_name', $routeNames)
            ->orWhereIn('title', [
                'Unit Ready',
                'Siteplan Unit Ready',
            ])
            ->pluck('id');

        if ($menuIds->isNotEmpty()) {
            $childIds = DB::table('menu')
                ->whereIn('id_parent', $menuIds)
                ->pluck('id');

            $deleteIds = $menuIds->merge($childIds)->unique()->values();

            DB::table('hak_akses')->whereIn('id_menu', $deleteIds)->delete();
            DB::table('menu')->whereIn('id', $deleteIds)->delete();
        }

        if (Schema::hasTable('kavling_peta') && Schema::hasColumn('kavling_peta', 'status_ready')) {
            Schema::table('kavling_peta', function (Blueprint $table) {
                $table->dropColumn('status_ready');
            });
        }

        Schema::dropIfExists('progres_unit_ready');
    }

    public function down(): void
    {
        //
    }
};
