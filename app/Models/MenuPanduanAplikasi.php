<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPanduanAplikasi extends Model
{
    use HasFactory;
    protected $table = 'menu_panduan_aplikasi';
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'link_yt',
        'deskripsi',
    ];
}
