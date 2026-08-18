<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_masuk',
        'id_barang',
        'jumlah',
        'harga_beli',
        'sub_total',
        'keterangan',
    ];
}
