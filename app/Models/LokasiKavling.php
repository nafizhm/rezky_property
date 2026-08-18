<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiKavling extends Model
{
    use HasFactory;

    protected $table = 'lokasi_kavling';

    public $timestamps = false;

    protected $fillable = [
        'nama_kavling',
        'nama_singkat',
        'alamat',
        'urutan',
        'header',
        'stt_tampil',
        'is_cluster',
        'no_kwitansi',
        'no_bast',
        'no_ppjb',
        'reset_nomor',
    ];

    public function kavlingPeta()
    {
        return $this->hasMany(KavlingPeta::class, 'id_lokasi', 'id');
    }

    public function masterSvg()
    {
        return $this->hasOne(MasterSVG::class, 'id_lokasi', 'id');
    }

    public function perusahaan()
    {
        return $this->hasMany(LokasiKavlingPerusahaan::class, 'id_lokasi');
    }

    public function getKotaPenandatanganAttribute()
    {
        return optional($this->perusahaan->first())->kota_penandatangan ?? '-';
    }
}
