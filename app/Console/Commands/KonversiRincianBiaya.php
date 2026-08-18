<?php

namespace App\Console\Commands;

use App\Models\KomponenBiaya;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KonversiRincianBiaya extends Command
{
    protected $signature = 'konversi:rincian-biaya';
    protected $description = 'Konversi format rincian_biaya dari {id,kode,nilai} ke {nama,nilai}';

    public function handle(): int
    {
        $komponen = KomponenBiaya::all()->keyBy('kode_unik');

        $this->info('Konversi data kavling_peta...');
        $total = 0;

        DB::table('kavling_peta')
            ->whereNotNull('rincian_biaya')
            ->orderBy('id')
            ->chunk(100, function ($kavlings) use ($komponen, &$total) {
                foreach ($kavlings as $k) {
                    $old = json_decode($k->rincian_biaya, true);
                    if (!$old || !is_array($old)) continue;

                    $new = [];
                    foreach ($old as $item) {
                        $kode = $item['kode'] ?? null;
                        $nilai = (int) ($item['nilai'] ?? 0);
                        $nama = $item['nama'] ?? ($kode ? ($komponen[$kode]->nama ?? $kode) : null);

                        if (!$nama || $nilai <= 0) continue;

                        $new[] = [
                            'nama'  => $nama,
                            'nilai' => $nilai,
                        ];
                    }

                    DB::table('kavling_peta')
                        ->where('id', $k->id)
                        ->update(['rincian_biaya' => !empty($new) ? json_encode($new) : null]);
                    $total++;
                }
            });

        $this->info("{$total} kavling berhasil dikonversi.");
        return Command::SUCCESS;
    }
}
