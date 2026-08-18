<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    public $timestamps = false;

    protected $fillable = [
        'sku',
        'nama',
        'id_satuan',
        'id_supplier',
        'stok_awal',
        'stok_minimal',
        'stok',
        'harga_beli',
        'deskripsi',
        'harga_jual',
    ];

}
