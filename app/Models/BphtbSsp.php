<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BphtbSsp extends Model
{
    use HasFactory;

    protected $table = 'bphtb_ssp';
    public $timestamps = false;
    protected $fillable = [
        'id_lokasi',
        'id_kavling',
        'status_bphtb',
        'status_ssp',
    ];
}
