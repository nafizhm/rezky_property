<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wawancara extends Model
{
    protected $table = 'wawancara';
    public $timestamps = false;

    protected $fillable = [
        'tgl_wawancara',
        'id_customer',
        'catatan_wawancara',
        'status',
        'id_bank_kpr'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function bankKPR()
    {
        return $this->belongsTo(BankKPR::class, 'id_bank_kpr');
    }

    public function wawancaraSp3k()
    {
        return $this->hasMany(WawancaraSp3k::class, 'id_wawancara');
    }
}
