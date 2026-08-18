<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    public $timestamps = false;

    protected $fillable = [
        'id_role',
        'id_menu',
        'lihat',
        'tambah',
        'edit',
        'hapus',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    // Relasi dengan model Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}
