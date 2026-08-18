<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retensi extends Model
{
    use HasFactory;

    protected $table = 'retensi';

    protected $fillable = [
        'nama_retensi',
        'keterangan',
    ];

    public $timestamps = false;
}
