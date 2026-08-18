<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class hak_aksesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hak_akses')->insert([
            [
                'id_hak_akses'     => 34,
                'id_user'   => 318,
                'id_menu'  => 92,
                'status_hak'         => 1,
                'lihat'  => 1,
                'tambah'        => 1,
                'edit'        => 1,
                'hapus'       => 1,
            ],
        ]);
    }
}
