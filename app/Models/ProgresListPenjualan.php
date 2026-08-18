<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgresListPenjualan extends Model
{
    use HasFactory;

    protected $table = 'progres_list_penjualan';

        protected $fillable = [
        'status_progres',
        'urutan',
        'keterangan',
        'warna',
        'short_name',
        'stt_tampil',
    ];

    public $timestamps = false;
}
