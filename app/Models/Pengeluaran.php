<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table    = 'pengeluaran';
    protected $fillable = [
        'id_hutang',
        'id_piutang',
        'id_po',
        'id_mutasi',
        'id_pembelian_cancel',
        'tanggal',
        'id_bank',
        'nominal',
        'lampiran',
        'id_kategori_transaksi',
        'id_metode_bayar',
        'keterangan',
    ];
    public $timestamps = false;
}
