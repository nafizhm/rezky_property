<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListPenjualan extends Model
{
    use HasFactory;

    protected $table = 'progres_list_penjualan';
    protected $fillable = [
        'status_progres',
        'keterangan',
        'urutan',
        'warna',
        'short_name',
        'warna_bootstrap',
        'stt_tampil',
    ];

    public $timestamps = false;

}
