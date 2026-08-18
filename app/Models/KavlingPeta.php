<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KavlingPeta extends Model
{
    use HasFactory;

    protected $table = 'kavling_peta';

    protected $fillable = [
        'id_lokasi',
        'cluster',
        'blok',
        'no',
        'id_perusahaan',
        'id_cluster',
        'kode_kavling',
        'panjang_kanan',
        'panjang_kiri',
        'lebar_depan',
        'lebar_belakang',
        'luas_tanah',
        'tipe_bangunan',
        'daya_listrik',
        'luas_bangunan',
        'hrg_meter',
        'rincian_biaya',
        'hrg_jual',
        'biaya_surat',
        'peningkatan_mutu',
        'id_rumah_sikumbang',
        'no_sertifikat',
        'jenis_map',
        'map',
        'matrik',
        'status',
        'keterangan',
        'atas_nama_surat',
        'id_customer',
        'tgl_jatuh_tempo',
        'tgl_jatuh_tempo',
        'stt_cicilan',
        'foto',
    ];

    protected $casts = [
        'rincian_biaya' => 'array',
    ];

    protected $appends = ['total_harga'];

    public function lokasi()
    {
        return $this->belongsTo(LokasiKavling::class, 'id_lokasi', 'id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id_kavling', 'id');
    }
    public function progres()
    {
        return $this->belongsTo(ProgresListPenjualan::class, 'id_status_progres', 'id');
    }
    public function perusahaan() {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }
    public function listrikAir()
    {
        return $this->hasOne(ListrikAir::class, 'id_kavling', 'id');
    }
    public function BphtbSsp()
    {
        return $this->hasOne(BphtbSsp::class, 'id_kavling', 'id');
    }
    public function BalikNama()
    {
        return $this->hasOne(BalikNama::class, 'id_kavling', 'id');
    }

    public function getTotalHargaAttribute(): int
    {
        return collect($this->rincian_biaya ?? [])->sum('nilai');
    }

    public function getRincianHargaHtmlAttribute(): string
    {
        if (!$this->rincian_biaya) return '<span class="text-muted">-</span>';

        $html = '<div class="w-100">';
        foreach ($this->rincian_biaya as $item) {
            $nama  = $item['nama'] ?? '-';
            $nilai = (int) ($item['nilai'] ?? 0);
            if ($nilai <= 0) continue;
            $html .= '<div class="d-flex justify-content-between harga-format">
                <span>' . e($nama) . ' : </span>
                <span>Rp. ' . number_format($nilai, 0, ',', '.') . '</span>
            </div>';
        }
        $html .= '</div>';
        return $html;
    }

    // Backward-compatible accessors: baca dari JSON, fallback ke kolom DB lama
    public function getHrgJualAttribute()
    {
        $nilai = $this->getNilaiByNama('Harga Rumah');
        if ($nilai === 0 && $this->rincian_biaya === null) {
            return (int) ($this->attributes['hrg_jual'] ?? 0);
        }
        return $nilai;
    }

    public function getBiayaSuratAttribute()
    {
        $nilai = $this->getNilaiByNama('Biaya Surat');
        if ($nilai === 0 && $this->rincian_biaya === null) {
            return (int) ($this->attributes['biaya_surat'] ?? 0);
        }
        return $nilai;
    }

    public function getPeningkatanMutuAttribute()
    {
        $nilai = $this->getNilaiByNama('Peningkatan Mutu');
        if ($nilai === 0 && $this->rincian_biaya === null) {
            return (int) ($this->attributes['peningkatan_mutu'] ?? 0);
        }
        return $nilai;
    }

    private function getNilaiByNama(string $nama): int
    {
        if (!$this->rincian_biaya) return 0;
        $item = collect($this->rincian_biaya)->firstWhere('nama', $nama);
        return (int) ($item['nilai'] ?? 0);
    }

    public $timestamps = false;
}
