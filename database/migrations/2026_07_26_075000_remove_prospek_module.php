<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $menuIds = DB::table('menu')
            ->where('route_name', 'prospek.index')
            ->orWhere('title', 'Prospek')
            ->pluck('id');

        if ($menuIds->isNotEmpty()) {
            DB::table('hak_akses')->whereIn('id_menu', $menuIds)->delete();
            DB::table('role_user')->whereIn('id_menu', $menuIds)->delete();
            DB::table('menu')->whereIn('id', $menuIds)->delete();
        }

        Schema::dropIfExists('prospek_customer');
    }

    public function down(): void
    {
        //
    }
};
