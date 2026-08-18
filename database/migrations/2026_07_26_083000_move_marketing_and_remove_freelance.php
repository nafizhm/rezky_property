<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $masterDataId = DB::table('menu')->where('title', 'Master Data')->value('id');

        if ($masterDataId) {
            DB::table('menu')
                ->where('route_name', 'marketing-offline.index')
                ->update([
                    'id_parent' => $masterDataId,
                    'title' => 'Marketing',
                    'icon' => 'far fa-circle',
                    'urutan' => 0,
                ]);
        }

        $removedMenuIds = DB::table('menu')
            ->where('route_name', 'marketing-freelance.index')
            ->orWhere(function ($query) {
                $query->where('title', 'Marketing')
                    ->where('route_name', '#')
                    ->where('id_parent', 0);
            })
            ->pluck('id');

        if ($removedMenuIds->isNotEmpty()) {
            DB::table('hak_akses')->whereIn('id_menu', $removedMenuIds)->delete();
            DB::table('role_user')->whereIn('id_menu', $removedMenuIds)->delete();
            DB::table('menu')->whereIn('id', $removedMenuIds)->delete();
        }

        Schema::disableForeignKeyConstraints();

        foreach (['customer', 'customer_tempo', 'pengajuan_hold', 'pengajuan_hold_tempo'] as $table) {
            if (Schema::hasColumn($table, 'id_freelance')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('id_freelance');
                });
            }
        }

        Schema::dropIfExists('marketing_freelance');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        //
    }
};
