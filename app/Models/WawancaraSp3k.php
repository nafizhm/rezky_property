<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WawancaraSp3k extends Model
{
    use HasFactory;

    protected $table = 'wawancara_sp3k';

    protected $fillable = [
        'id_wawancara',
        'id_bank_kpr',
        'acc_plafon',
        'tenor',
        'id_notaris',
        'tgl_terbit_sp3k',
        'no_sp3k',
        'tgl_expired',
        'catatan_acc',
        'status',
        'lampiran',
    ];

    public $timestamps = false;

    public function bankKPR()
    {
        return $this->belongsTo(BankKPR::class, 'id_bank_kpr');
    }
    public function notaris()
    {
        return $this->belongsTo(Notaris::class, 'id_notaris');
    }
    public function wawancara()
    {
        return $this->belongsTo(Wawancara::class, 'id_wawancara');
    }
}
