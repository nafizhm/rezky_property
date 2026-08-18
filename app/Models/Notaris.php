<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notaris extends Model
{
    use HasFactory;

    protected $table = 'notaris';
    public $timestamps = false;

    protected $fillable = [
        'nama_notaris',
        'alamat_notaris',
        'telp_notaris',
        'keterangan_notaris',
    ];

}
