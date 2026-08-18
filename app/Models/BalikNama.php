<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BalikNama extends Model
{
    use HasFactory;

    protected $table = 'balik_nama';
    public $timestamps = false;

    protected $fillable = [
        'id_kavling',
        'id_lokasi',
        'id_customer',
        'nama_pengganti',
        'stt_balik',
    ];

 
}
