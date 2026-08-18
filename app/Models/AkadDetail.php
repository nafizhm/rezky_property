<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkadDetail extends Model
{
    use HasFactory;

    protected $table = 'detail_akad';
    public $timestamps = false;

    protected $fillable = [
        'id_akad',
        'id_customer',
        'id_persyaratan',
        'status',
        'keterangan',
        'jenis_akad',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function persyaratan()
    {
        return $this->belongsTo(PersyaratanLegal::class, 'id_persyaratan');
    }

}
