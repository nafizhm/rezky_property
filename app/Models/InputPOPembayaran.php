<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InputPOPembayaran extends Model
{
    use HasFactory;
    protected $table = 'input_po_pembayaran';
    public $timestamps = false;

    protected $fillable = [
        'id_po',
        'tanggal',
        'terbayar',
        'lampiran',
    ];
}
