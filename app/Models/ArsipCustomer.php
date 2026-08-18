<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipCustomer extends Model
{
    use HasFactory;

    protected $table = 'arsip_customer';

    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'id_customer',
        'id_lokasi',
        'id_kavling',
        'id_status_progres',
        'kode_customer',
        'nama_lengkap',
        'no_ktp',
        'no_ktp_p',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'alamat_ktp',
        'alamat_domisili',
        'no_telp',
        'pekerjaan',
        'id_marketing',
    ];

    public function marketing()
    {
        return $this->belongsTo(MarketingOffline::class, 'id_marketing');
    }

    public function progres()
    {
        return $this->belongsTo(ProgresListPenjualan::class, 'id_status_progres');
    }

    public function lokasiKavling()
    {
        return $this->belongsTo(LokasiKavling::class, 'id_lokasi');
    }

    public function kavlingPeta()
    {
        return $this->belongsTo(KavlingPeta::class, 'id_kavling');
    }
}
