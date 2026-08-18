<?php
namespace App\Services;

use App\Models\Customer;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\Perusahaan;
use Carbon\Carbon;

class DocumentDataContext
{
    public static function fromCustomer(Customer $customer): array
    {
        $tglVerif = $customer->tanggal_verif
            ? Carbon::parse($customer->tanggal_verif)
            : Carbon::now();

        return [
            'id_customer'      => $customer->id,
            'kode_customer'    => $customer->kode_customer ?? '-',
            'nama_lengkap'     => $customer->nama_lengkap ?? '-',
            'nik'              => $customer->nik ?? '-',
            'jenis_kelamin'    => $customer->jenis_kelamin ?? '-',
            'tempat_lahir'     => $customer->tempat_lahir ?? '-',
            'tgl_lahir'        => $customer->tgl_lahir
                ? Carbon::parse($customer->tgl_lahir)->translatedFormat('d F Y')
                : '-',
            'pekerjaan'        => $customer->pekerjaan ?? '-',
            'alamat_ktp'       => $customer->alamat_ktp ?? '-',
            'alamat_domisili'  => $customer->alamat_domisili ?? '-',
            'alamat'           => $customer->alamat_domisili ?? '-',
            'no_telp'          => $customer->no_telp ?? '-',
            'email'            => $customer->email ?? '-',
            'npwp'             => $customer->npwp ?? '-',
            'nama_pasangan'    => $customer->nama_p ?? '-',
            'nik_pasangan'     => $customer->nik_p ?? '-',
            'nama_lengkap_p'   => $customer->nama_p ?? '-',
            'nik_p'            => $customer->nik_p ?? '-',
            'tgl_sekarang'     => Carbon::now()->translatedFormat('d F Y'),
            'hari_ini'         => Carbon::now()->translatedFormat('l'),
            'bulan_ini'        => Carbon::now()->translatedFormat('F'),
            'tahun_ini'        => Carbon::now()->format('Y'),
            'no_ktp'           => $customer->nik ?? '-',
            'no_wa'            => $customer->no_telp ?? '-',
            'tanggal'          => $tglVerif->format('d'),
            'bulan'            => $tglVerif->locale('id')->isoFormat('MMMM'),
            'tahun'            => $tglVerif->format('Y'),
            'tanggal_lahir'    => $customer->tgl_lahir
                ? Carbon::parse($customer->tgl_lahir)->translatedFormat('d F Y')
                : '-',
        ];
    }

    public static function fromKavlingPeta(?KavlingPeta $kavling): array
    {
        if (!$kavling) return [];

        return [
            'id_kavling'              => $kavling->id,
            'kode_kavling'            => $kavling->kode_kavling ?? '-',
            'blok'                    => $kavling->blok ?? '-',
            'no_rumah'                => $kavling->kode_kavling ?? '-',
            'no_blok'                 => $kavling->kode_kavling ?? '-',
            'tipe_rumah'              => (string) ($kavling->tipe_bangunan ?? '-'),
            'luas_tanah'              => (string) ($kavling->luas_tanah ?? '-'),
            'luas_bangunan'           => (string) ($kavling->luas_bangunan ?? '-'),
            'daya_listrik'            => $kavling->daya_listrik ?? '-',
            'harga_jual'              => $kavling->hrg_jual
                ? number_format($kavling->hrg_jual, 0, ',', '.')
                : '0',
            'harga_jual_terbilang'    => $kavling->hrg_jual
                ? self::terbilang($kavling->hrg_jual)
                : '-',
            'hrg_jual'                => $kavling->hrg_jual
                ? number_format($kavling->hrg_jual, 0, ',', '.')
                : '0',
            'lebar_depan'             => (string) ($kavling->lebar_depan ?? '-'),
            'lebar_belakang'          => (string) ($kavling->lebar_belakang ?? '-'),
            'panjang_kanan'           => (string) ($kavling->panjang_kanan ?? '-'),
            'panjang_kiri'            => (string) ($kavling->panjang_kiri ?? '-'),
            'blok_kavling'            => $kavling->kode_kavling ?? '-',
            'tipe'                    => (string) ($kavling->tipe_bangunan ?? '-'),
            'luas_tanah_standar'      => (string) ($kavling->luas_tanah ?? '-'),
            'lebihan_luas'            => '0',
        ];
    }

    public static function fromLokasi(?LokasiKavling $lokasi): array
    {
        if (!$lokasi) return [];

        return [
            'nama_perumahan'   => $lokasi->nama_kavling ?? '-',
            'lokasi_rumah'     => $lokasi->nama_kavling ?? '-',
            'lokasi_perumahan' => $lokasi->nama_kavling ?? '-',
            'alamat_perumahan' => $lokasi->alamat ?? '-',
        ];
    }

    public static function fromPerusahaan(?Perusahaan $perusahaan): array
    {
        if (!$perusahaan) return [];

        return [
            'nama_developer'           => $perusahaan->nama_perusahaan ?? '-',
            'alamat_perusahaan'        => $perusahaan->alamat_perusahaan ?? '-',
            'nama_penandatangan'       => $perusahaan->nama_penandatangan ?? '-',
            'form_nama_penanda_tangan' => $perusahaan->nama_penandatangan ?? '-',
            'form_nik'                 => $perusahaan->form_nik ?? $perusahaan->nik_penandatangan ?? '-',
            'jabatan_penandatangan'    => $perusahaan->jabatan_penandatangan ?? '-',
            'form_jabatan'             => $perusahaan->jabatan_penandatangan ?? '-',
            'kota_penandatangan'       => $perusahaan->kota_penandatangan ?? '-',
            'nama_perusahaan'          => $perusahaan->nama_perusahaan ?? '-',
        ];
    }

    public static function getAllForCustomer(Customer $customer): array
    {
        $kavling    = $customer->kavlingPeta;
        $lokasi     = $kavling?->lokasi ?? $customer->lokasiKavling;
        $perusahaan = $kavling?->perusahaan;

        return array_merge(
            self::fromCustomer($customer),
            self::fromKavlingPeta($kavling),
            self::fromLokasi($lokasi),
            self::fromPerusahaan($perusahaan),
        );
    }

    public static function getContextKeys(): array
    {
        return [
            'Customer' => [
                'id_customer', 'kode_customer', 'nama_lengkap', 'nik',
                'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'pekerjaan',
                'alamat_ktp', 'alamat_domisili', 'alamat', 'no_telp',
                'email', 'npwp', 'nama_pasangan', 'nik_pasangan',
                'nama_lengkap_p', 'nik_p', 'no_ktp', 'no_wa', 'tanggal',
                'bulan', 'tahun', 'tanggal_lahir',
            ],
            'Tanggal Cetak' => [
                'tgl_sekarang', 'hari_ini', 'bulan_ini', 'tahun_ini',
            ],
            'Kavling' => [
                'id_kavling', 'kode_kavling', 'blok', 'no_rumah', 'no_blok',
                'tipe_rumah', 'tipe', 'luas_tanah', 'luas_tanah_standar',
                'luas_bangunan', 'daya_listrik',
                'harga_jual', 'harga_jual_terbilang', 'hrg_jual',
                'lebar_depan', 'lebar_belakang', 'panjang_kanan', 'panjang_kiri',
                'blok_kavling', 'lebihan_luas',
            ],
            'Lokasi' => [
                'nama_perumahan', 'lokasi_rumah', 'lokasi_perumahan', 'alamat_perumahan',
            ],
            'Perusahaan' => [
                'nama_developer', 'alamat_perusahaan', 'nama_penandatangan',
                'form_nama_penanda_tangan', 'form_nik', 'jabatan_penandatangan',
                'form_jabatan', 'kota_penandatangan', 'nama_perusahaan',
            ],
        ];
    }

    public static function terbilang($angka): string
    {
        $f = new \NumberFormatter("id", \NumberFormatter::SPELLOUT);
        return ucwords($f->format($angka)) . ' Rupiah';
    }
}
