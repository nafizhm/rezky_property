<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTempo extends Model
{
    use HasFactory;
    protected $table = 'customer_tempo';
    protected $fillable = [
        'kode_customer',
        'tanggal_verif',
        'id_lokasi',
        'id_kavling',
        'hrg_jual',
        'biaya_surat',
        'peningkatan_mutu',
        'total_harga',
        'id_status_progres',
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'alamat_ktp',
        'alamat_domisili',
        'status_pernikahan',
        'nama_p',
        'nik_p',
        'no_bpjs_kes',
        'nama_saudara',
        'no_telp_saudara',
        'jenis_perumahan',
        'no_telp',
        'email',
        'npwp',
        'pekerjaan',
        'id_marketing',
        'jenis_pembelian',
        'an_surat_cash',
        'termin_x_cash_b',
        'stt_arsip',
        'id_customer',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function persyaratan()
    {
        return $this->hasOne(PersyaratanLegal::class, 'id_customer');
    }

    public function marketing()
    {
        return $this->belongsTo(MarketingOffline::class, 'id_marketing');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiKavling::class, 'id_lokasi');
    }

    public function kavling()
    {
        return $this->belongsTo(KavlingPeta::class, 'id_kavling');
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

    public function akadDetail()
    {
        return $this->hasMany(AkadDetail::class, 'id_customer');
    }

    public function piutangs()
    {
        return $this->hasMany(Piutang::class, 'id_customer');
    }

    public function pemasukans()
    {
        return $this->hasMany(Pemasukan::class, 'id_customer');
    }

    public function wawancara()
    {
        return $this->hasMany(Wawancara::class, 'id_customer');
    }

    public $timestamps = false;
}
