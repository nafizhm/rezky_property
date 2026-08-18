<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_barang_keluar',
        'id_barang',
        'jumlah',
    ];
}
