<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    public $timestamps = false;

    protected $fillable = [
        'id_po',
        'tanggal',
        'nama_penerima'
    ];
}
