<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InputPODetail extends Model
{
     use HasFactory;
    protected $table = 'input_po_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_po',
        'id_barang',
        'jumlah',
        'harga_beli',
        'sub_total',
    ];
}
