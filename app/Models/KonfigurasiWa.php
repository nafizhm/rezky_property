<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiWa extends Model
{
    //
    use HasFactory;

    protected $table = 'konfigurasi_wa';

    protected $fillable = [
        'api_key',
        'number_key',

    ];

    public $timestamps = false;

}
