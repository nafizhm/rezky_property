<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanRetensi extends Model
{
    use HasFactory;

    protected $table = 'pemasukan_retensi';

    protected $fillable = [
        'id_pemasukan',
        'id_retensi',
        'nominal',
    ];

    public $timestamps = false;

    public function retensi()
    {
        return $this->belongsTo(Retensi::class, 'id_retensi');
    }

    public function pemasukan()
    {
        return $this->belongsTo(Pemasukan::class, 'id_pemasukan');
    }
}
