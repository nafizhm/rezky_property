<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $menuIds = DB::table('menu')
            ->where('route_name', 'konten.index')
            ->orWhere(function ($query) {
                $query->where('title', 'Konten')
                    ->where('id_parent', function ($subQuery) {
                        $subQuery->select('id')
                            ->from('menu')
                            ->where('title', 'Pengaturan')
                            ->limit(1);
                    });
            })
            ->pluck('id');

        if ($menuIds->isNotEmpty()) {
            DB::table('hak_akses')->whereIn('id_menu', $menuIds)->delete();
            DB::table('role_user')->whereIn('id_menu', $menuIds)->delete();
            DB::table('menu')->whereIn('id', $menuIds)->delete();
        }
    }

    public function down(): void
    {
        //
    }
};
