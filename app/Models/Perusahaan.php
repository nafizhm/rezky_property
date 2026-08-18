<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';
    public $timestamps = false;
    protected $fillable = [
        'nama_perusahaan',
        'alamat_perusahaan',
        'telp_perusahaan',
        'bg_kwitansi',
        'kop_surat',
        'kota_penandatangan',
        'nama_penandatangan',
        'jabatan_penandatangan',
        'nama_mengetahui',
    ];
    public function lokasiKavling()
    {
        return $this->belongsToMany(
            LokasiKavling::class,
            'kavling_perusahaan',
            'id_perusahaan',
            'id_lokasi'
        );
    }
}
