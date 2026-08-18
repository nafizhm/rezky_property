<?php

namespace Database\Seeders;

use App\Models\KomponenBiaya;
use Illuminate\Database\Seeder;

class KomponenBiayaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_unik'  => 'harga_jual',
                'nama'       => 'Harga Rumah',
                'deskripsi'  => 'Harga jual rumah/kavling',
                'urutan'     => 1,
                'wajib'      => true,
                'aktif'      => true,
                'satuan'     => 'Rp',
            ],
            [
                'kode_unik'  => 'biaya_surat',
                'nama'       => 'Biaya Surat',
                'deskripsi'  => 'Biaya pengurusan dokumen surat',
                'urutan'     => 2,
                'wajib'      => false,
                'aktif'      => true,
                'satuan'     => 'Rp',
            ],
            [
                'kode_unik'  => 'peningkatan_mutu',
                'nama'       => 'Peningkatan Mutu',
                'deskripsi'  => 'Biaya peningkatan mutu bangunan',
                'urutan'     => 3,
                'wajib'      => false,
                'aktif'      => true,
                'satuan'     => 'Rp',
            ],
        ];

        foreach ($data as $item) {
            KomponenBiaya::create($item);
        }
    }
}
