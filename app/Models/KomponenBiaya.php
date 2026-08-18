<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenBiaya extends Model
{
    protected $table = 'komponen_biaya';

    protected $fillable = [
        'kode_unik',
        'nama',
        'deskripsi',
        'urutan',
        'wajib',
        'aktif',
        'satuan',
    ];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeUrut($query)
    {
        return $query->orderBy('urutan');
    }
}
