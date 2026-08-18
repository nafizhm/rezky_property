<?php
namespace App\Http\Controllers;

use Carbon\Carbon;

class GenerateNumberController extends Controller
{
    public function generateNomorDokumen($lokasi, $jenis, $model, $relasiLokasi = 'customer')
    {
        $bulanRomawi = [
            1 => 'I', 2   => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6   => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        $now   = Carbon::now('Asia/Jakarta');
        $tahun = $now->year;
        $bulan = $bulanRomawi[$now->month];

        $format = $lokasi->{$jenis};

        if (! $format) {
            throw new \Exception('Format nomor tidak ditemukan');
        }

        $lastData = $model::whereHas($relasiLokasi, function ($q) use ($lokasi) {
            $q->where('id_lokasi', $lokasi->id);
        })
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $urut = 1;

        if ($lastData) {
            if (preg_match('/^(\d{4}).*\/(\d{4})$/', $lastData->{$jenis}, $match)) {
                $lastUrut  = (int) $match[1];
                $lastTahun = (int) $match[2];

                if ((int) $lokasi->reset_nomor === 1 && $lastTahun !== $tahun) {
                    $urut = 1;
                } else {
                    $urut = $lastUrut + 1;
                }
            }
        }

        $urut = str_pad($urut, 4, '0', STR_PAD_LEFT);

        $nomor = $format;
        $nomor = str_replace('0000', $urut, $nomor);
        $nomor = str_replace('MM', $bulan, $nomor);
        $nomor = str_replace('YYYY', $tahun, $nomor);

        return $nomor;
    }
}
