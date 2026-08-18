<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menu')
            ->where('route_name', 'pengajuan-hold.index')
            ->update(['title' => 'Booking']);
    }

    public function down(): void
    {
        DB::table('menu')
            ->where('route_name', 'pengajuan-hold.index')
            ->update(['title' => 'Pengajuan Hold']);
    }
};
