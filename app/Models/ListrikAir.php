<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListrikAir extends Model
{
    use HasFactory;

    protected $table = 'listrik_air';

    public $timestamps = false;

    protected $fillable = [
        'id_lokasi',
        'id_kavling',
        'norek_listrik',
        'foto_listrik',
        'foto_listrik_2',
        'norek_air',
        'foto_air',
        'foto_air_2',
    ];
}
