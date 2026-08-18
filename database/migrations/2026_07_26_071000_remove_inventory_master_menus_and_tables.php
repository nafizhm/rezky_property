<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $routeNames = [
            'siteplan-proyek.index',
            'barang.index',
            'supplier.index',
            'satuan.index',
            'input-po.index',
            'barang-masuk.index',
            'barang-keluar.index',
        ];

        $menuIds = DB::table('menu')
            ->whereIn('route_name', $routeNames)
            ->orWhereIn('title', [
                'Siteplan Proyek',
                'Barang',
                'Supplier',
                'Satuan',
                'Input PO',
                'Barang Masuk',
                'Barang Keluar',
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

        Schema::disableForeignKeyConstraints();

        foreach ([
            'barang_masuk_detail',
            'barang_masuk',
            'barang_keluar_detail',
            'barang_keluar',
            'input_po_pembayaran',
            'input_po_detail',
            'input_po',
            'barang',
            'supplier',
            'satuan',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        //
    }
};
