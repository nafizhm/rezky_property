<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FotoProgres extends Model
{
    use HasFactory;

    protected $table = 'foto_progres';

    protected $fillable = [
        'id_progres_pembangunan',
        'id_kavling',
        'file_foto',
    ];

    public $timestamps = false;

}
