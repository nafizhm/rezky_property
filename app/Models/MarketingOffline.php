<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingOffline extends Model
{
    use HasFactory;
    protected $table    = 'marketing_offline';
    protected $fillable = [
        'kode_marketing',
        'nama_marketing',
        'jenis_kelamin',
        'alamat',
        'email',
        'no_telp',
        'pekerjaan',
        'sosmed',
        'nama_bank',
        'no_rekening',
        'atas_nama',
        'status',
        'foto',
    ];

    public $timestamps = false;
}
