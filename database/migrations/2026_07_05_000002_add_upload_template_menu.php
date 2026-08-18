<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $parent = DB::table('menu')->where('title', 'Master Data')->first();

        if ($parent) {
            DB::table('menu')->insert([
                'id_parent'  => $parent->id,
                'title'      => 'Upload Template',
                'route_name' => 'upload-template.index',
                'icon'       => 'fa-file-upload',
                'urutan'     => 99,
                'lihat'      => 1,
                'tambah'     => 1,
                'edit'       => 1,
                'hapus'      => 1,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu')->where('route_name', 'upload-template.index')->delete();
    }
};
