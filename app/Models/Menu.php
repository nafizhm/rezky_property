<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'id_parent',
        'title',
        'route_name',
        'icon',
        'urutan',
        'lihat',
        'tambah',
        'edit',
        'hapus',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'id_parent')->orderBy('urutan');
    }

    public function hakAkses()
    {
        return $this->hasMany(HakAkses::class, 'id_menu')->onDelete('cascade');
    }
}
