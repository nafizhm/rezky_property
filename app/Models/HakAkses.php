<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HakAkses extends Model
{
    use HasFactory;

    protected $table = 'hak_akses';
    public $timestamps = false;
    
    protected $fillable = [
        'id_user',
        'id_menu',
        'lihat',
        'beranda',
        'tambah',
        'edit',
        'hapus',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi dengan model Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}
