<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'bank';
    public $timestamps = false;
    protected $fillable = [
        'nama',
        'no_rek',
        'pemilik_rek',
    ];
}
