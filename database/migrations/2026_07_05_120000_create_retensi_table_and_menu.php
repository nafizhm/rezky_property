<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('retensi')) {
            Schema::create('retensi', function (Blueprint $table) {
                $table->id();
                $table->string('nama_retensi')->unique();
                $table->text('keterangan')->nullable();
            });
        }

        $masterDataMenu = DB::table('menu')->where('title', 'Master Data')->first();

        if ($masterDataMenu) {
            $existingMenu = DB::table('menu')->where('route_name', 'retensi.index')->first();

            if (! $existingMenu) {
                $maxUrutan = DB::table('menu')
                    ->where('id_parent', $masterDataMenu->id)
                    ->max('urutan');

                $menuId = DB::table('menu')->insertGetId([
                    'id_parent' => $masterDataMenu->id,
                    'title' => 'Retensi',
                    'route_name' => 'retensi.index',
                    'icon' => 'far fa-circle',
                    'urutan' => ($maxUrutan ?? 0) + 1,
                    'lihat' => 1,
                    'tambah' => 1,
                    'edit' => 1,
                    'hapus' => 1,
                ]);

                $roleColumn = Schema::hasColumn('users', 'id_role')
                    ? 'id_role'
                    : (Schema::hasColumn('users', 'role') ? 'role' : null);

                $users = $roleColumn
                    ? DB::table('users')->select('id', $roleColumn)->get()
                    : DB::table('users')->select('id')->get();
                $hakAkses = [];

                foreach ($users as $user) {
                    $roleValue = $roleColumn ? ($user->{$roleColumn} ?? null) : null;
                    $isDefaultAllowed = in_array((int) $roleValue, [2, 4], true);

                    $hakAkses[] = [
                        'id_user' => $user->id,
                        'id_menu' => $menuId,
                        'lihat' => $isDefaultAllowed ? 1 : 0,
                        'tambah' => $isDefaultAllowed ? 1 : 0,
                        'edit' => $isDefaultAllowed ? 1 : 0,
                        'hapus' => $isDefaultAllowed ? 1 : 0,
                    ];
                }

                if (! empty($hakAkses)) {
                    $existingHakAkses = DB::table('hak_akses')
                        ->where('id_menu', $menuId)
                        ->pluck('id_user')
                        ->toArray();

                    $hakAkses = array_filter($hakAkses, function ($item) use ($existingHakAkses) {
                        return ! in_array($item['id_user'], $existingHakAkses);
                    });

                    if (! empty($hakAkses)) {
                        DB::table('hak_akses')->insert($hakAkses);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        $menu = DB::table('menu')->where('route_name', 'retensi.index')->first();

        if ($menu) {
            DB::table('hak_akses')->where('id_menu', $menu->id)->delete();
            DB::table('menu')->where('id', $menu->id)->delete();
        }

        Schema::dropIfExists('retensi');
    }
};
