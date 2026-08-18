<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GantiNama extends Model
{
    protected $table = 'ganti_nama';
    public $timestamps = false;
    protected $fillable = [
        'tgl_ganti',
        'id_customer_lama',
        'id_customer_baru',
        'biaya_ganti_nama',
        'keterangan_ganti',
        'lampiran_bukti',
    ];

    public function customerLama()
    {
        return $this->belongsTo(Customer::class, 'id_customer_lama');
    }
    
    public function customerBaru()
    {
        return $this->belongsTo(Customer::class, 'id_customer_baru');
    }
}
