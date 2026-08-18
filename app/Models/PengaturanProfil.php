<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengaturanProfil extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi';

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'email',
        'telp',
        'hape',
        'npwp_perusahaan',
        'front_page',
    ];

    public $timestamps = false;

}
