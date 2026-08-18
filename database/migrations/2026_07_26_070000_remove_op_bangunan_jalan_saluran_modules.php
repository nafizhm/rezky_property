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
            'siteplan-proyek.index',
            'proyek-bangunan.index',
            'jenis-pekerjaan-bangunan.index',
            'proyek-jalan.index',
            'jalan.index',
            'jenis-pekerjaan-jalan.index',
            'proyek-saluran.index',
            'saluran.index',
            'jenis-pekerjaan-saluran.index',
        ];

        $menuIds = DB::table('menu')
            ->whereIn('route_name', $routeNames)
            ->orWhereIn('title', ['OP Bangunan', 'OP Jalan', 'OP Saluran'])
            ->pluck('id');

        if ($menuIds->isNotEmpty()) {
            $childIds = DB::table('menu')
                ->whereIn('id_parent', $menuIds)
                ->pluck('id');

            $deleteIds = $menuIds->merge($childIds)->unique()->values();

            DB::table('hak_akses')->whereIn('id_menu', $deleteIds)->delete();
            DB::table('menu')->whereIn('id', $deleteIds)->delete();
        }

        if (Schema::hasTable('barang_keluar')) {
            Schema::table('barang_keluar', function (Blueprint $table) {
                foreach (['jenis_proyek', 'id_proyek_bangunan', 'id_proyek_jalan', 'id_proyek_saluran'] as $column) {
                    if (Schema::hasColumn('barang_keluar', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('pengeluaran')) {
            Schema::table('pengeluaran', function (Blueprint $table) {
                foreach (['id_proyek_bangunan_detail', 'id_proyek_jalan_detail', 'id_proyek_saluran_detail'] as $column) {
                    if (Schema::hasColumn('pengeluaran', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        foreach ([
            'foto_proyek_bangunan',
            'foto_proyek_jalan',
            'foto_proyek_saluran',
            'proyek_bangunan_detail_kerja',
            'proyek_bangunan_detail',
            'proyek_bangunan_blok',
            'proyek_bangunan_unit',
            'proyek_bangunan',
            'proyek_jalan_detail_kerja',
            'proyek_jalan_detail',
            'proyek_jalan',
            'proyek_saluran_detail_kerja',
            'proyek_saluran_detail',
            'proyek_saluran',
            'jenis_pekerjaan_bangunan',
            'jenis_pekerjaan_jalan',
            'jenis_pekerjaan_saluran',
            'saluran',
            'jalan',
            'progres_list_pembangunan',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('barang_keluar')) {
            Schema::table('barang_keluar', function (Blueprint $table) {
                if (! Schema::hasColumn('barang_keluar', 'jenis_proyek')) {
                    $table->string('jenis_proyek')->default('')->after('tanggal');
                }

                if (! Schema::hasColumn('barang_keluar', 'id_proyek_bangunan')) {
                    $table->integer('id_proyek_bangunan')->nullable()->after('jenis_proyek');
                }

                if (! Schema::hasColumn('barang_keluar', 'id_proyek_jalan')) {
                    $table->integer('id_proyek_jalan')->nullable()->after('id_proyek_bangunan');
                }

                if (! Schema::hasColumn('barang_keluar', 'id_proyek_saluran')) {
                    $table->integer('id_proyek_saluran')->nullable()->after('id_proyek_jalan');
                }
            });
        }

        if (Schema::hasTable('pengeluaran')) {
            Schema::table('pengeluaran', function (Blueprint $table) {
                if (! Schema::hasColumn('pengeluaran', 'id_proyek_bangunan_detail')) {
                    $table->integer('id_proyek_bangunan_detail')->default(0)->after('id_mutasi');
                }

                if (! Schema::hasColumn('pengeluaran', 'id_proyek_jalan_detail')) {
                    $table->integer('id_proyek_jalan_detail')->default(0)->after('id_proyek_bangunan_detail');
                }

                if (! Schema::hasColumn('pengeluaran', 'id_proyek_saluran_detail')) {
                    $table->integer('id_proyek_saluran_detail')->default(0)->after('id_proyek_jalan_detail');
                }
            });
        }
    }
};
