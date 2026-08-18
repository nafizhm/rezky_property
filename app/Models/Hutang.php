<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hutang extends Model
{
    use HasFactory;

    protected $table = 'hutang';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_hutang',
        'deskripsi',
        'id_bank',
        'nominal',
        'lampiran',
        'status',
        'terbayar',
        'sisa_bayar',
        'tgl_pelunasan',
    ];
}
