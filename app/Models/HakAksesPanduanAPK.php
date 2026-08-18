<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakAksesPanduanAPK extends Model
{
    use HasFactory;
    protected $table = 'hak_akses_panduan_apk';
    public $timestamps = false;

    protected $fillable = [
        'id_role',
        'id_menu_panduan',
        'akses',
    ];
}
