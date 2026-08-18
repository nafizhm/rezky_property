<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu')->insert([
            [
                'id_menu'     => 92,
                'id_parent'   => 90,
                'title_menu'  => 'Prospek',
                'url'         => 'prospekData',
                'link_aktif'  =>  0,
                'icon'        => 'far fa-circle',
                'urutan'      => 1,
                'status_menu' => 1,
                'lihat'       => 1,
                'tambah'      => 1,
                'edit'        => 1,
                'hapus'       => 1,
            ],
        ]);
    }
}
