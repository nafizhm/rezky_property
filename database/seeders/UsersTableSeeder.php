<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id'       => 1,
                'surname'  => 'SUPERADMIN',
                'username' => 'master',
                'password' => Hash::make('admin'),
                'email'    => 'master@gmail.com',
                'status'   => 'AKTIF',
                'is_admin'     =>  4,
                'id_divisi'     =>  1,
                'id_join'     =>  0,
                'is_trash'     =>  0,
            ]
        ]);
    }
}
