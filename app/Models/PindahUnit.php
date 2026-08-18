<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PindahUnit extends Model
{
    protected $table   = 'pindah_unit';
    public $timestamps = false;

    protected $fillable = [
        'tgl_pindah',
        'id_customer',
        'id_kavling_lama',
        'id_kavling_baru',
        'nominal_utj',
        'biaya_admin',
        'keterangan_pindah',
        'id_bank',
        'id_metode_bayar',
        'lampiran_bukti',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function kavlingLama()
    {
        return $this->belongsTo(KavlingPeta::class, 'id_kavling_lama');
    }

    public function kavlingBaru()
    {
        return $this->belongsTo(KavlingPeta::class, 'id_kavling_baru');
    }

}
