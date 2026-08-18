<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankKPR extends Model
{
    protected $table = 'bank_kpr';
    public $timestamps = false;
    protected $fillable = [
        'nama',
    ];
}
