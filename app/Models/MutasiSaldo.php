<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MutasiSaldo extends Model
{
    use HasFactory;

    protected $table = 'mutasi_saldo';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'rekening_asal',
        'rekening_tujuan',
        'nominal',
        'lampiran',
        'keterangan',
    ];
}