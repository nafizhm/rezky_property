<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Piutang extends Model
{
    use HasFactory;

    protected $table = 'piutang';
    public $timestamps = false;

    protected $fillable = [
        'id_invoice',
        'tanggal_piutang',
        'id_bank',
        'id_customer',
        'deskripsi',
        'nominal',
        'lampiran',
        'status',
        'terbayar',
        'sisa_bayar',
        'tgl_pelunasan',
        'id_kategori_transaksi',
        'id_komponen_biaya',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriTransaksi::class, 'id_kategori_transaksi');
    }
}
