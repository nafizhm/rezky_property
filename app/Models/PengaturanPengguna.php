<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanPengguna extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'surname',
        'username',
        'password',
        'email',
        'status',
        'id_role',
        'id_marketing',
    ];

    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

}
