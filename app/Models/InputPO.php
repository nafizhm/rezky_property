<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InputPO extends Model
{
    use HasFactory;
    protected $table = 'input_po';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'id_supplier',
        'keterangan',
        'no_po',
        'jum_item',
        'total_harga',
        'terbayar',
        'status',
        'lampiran_po',
        'id_bank',
    ];
}
