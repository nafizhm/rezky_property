<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BAST extends Model
{    protected $table = 'bast';
    public $timestamps = false;
    protected $fillable = [
        'id_customer',
        'tanggal_bast',
        'no_bast',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

}
