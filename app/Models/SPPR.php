<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SPPR extends Model
{
    protected $table = 'sppr';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_customer',
        'no_sppr',
        'nama',
        'alamat',
        'nik',
        'no_telp',
        'luas_bangunan',
        'luas_tanah',
        'blok',
        'no',
        'harga_jual',
        'asumsi_plafon_kpr',
        'biaya_surat_surat',
        'peningkatan_mutu',
        'biaya_kelebihan_tanah',
        'biaya_sudut',
        'biaya_lain_lain',
        'total_yang_harus_dibayar',
        'jumlah_booking_fee',
        'cicilan_per_bulan',
        'id_marketing',
        'penandatangan',
        'keterangan',
        'agama',
        'pekerjaan',
        'promo',
        'perubahan_posisi',
        'keterangan_booking',
        'nominal_dp',
        'keterangan_dp',
        'nominal_biaya_posisi_unit',
        'keterangan_posisi_unit',
        'nominal_biaya_kpr',
        'keterangan_kpr',
        'nominal_blokir_angsuran',
        'keterangan_blokir_angsuran',
        'nominal_biaya_materai',
        'keterangan_materai',
        'nominal_biaya_buka_tabungan',
        'keterangan_tabungan',
        'keterangan_shm',
    ];

    protected $casts = [
        'luas_bangunan' => 'integer',
        'luas_tanah' => 'integer',
        'harga_jual' => 'integer',
        'asumsi_plafon_kpr' => 'integer',
        'biaya_surat_surat' => 'integer',
        'peningkatan_mutu' => 'integer',
        'biaya_kelebihan_tanah' => 'integer',
        'biaya_sudut' => 'integer',
        'biaya_lain_lain' => 'integer',
        'total_yang_harus_dibayar' => 'integer',
        'jumlah_booking_fee' => 'integer',
        'cicilan_per_bulan' => 'integer',
        'id_marketing' => 'integer',
        'nominal_dp' => 'integer',
        'nominal_biaya_posisi_unit' => 'integer',
        'nominal_biaya_kpr' => 'integer',
        'nominal_blokir_angsuran' => 'integer',
        'nominal_biaya_materai' => 'integer',
        'nominal_biaya_buka_tabungan' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function marketing()
    {
        return $this->belongsTo(MarketingOffline::class, 'id_marketing');
    }
}
