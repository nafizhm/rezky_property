<?php

namespace App\Console\Commands;

use App\Models\KomponenBiaya;
use App\Models\Piutang;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrasiRincianBiaya extends Command
{
    protected $signature = 'migrasi:rincian-biaya';
    protected $description = 'Migrasi data harga_jual, biaya_surat, peningkatan_mutu ke kolom JSON rincian_biaya';

    public function handle(): int
    {
        $komponen = KomponenBiaya::all()->keyBy('kode_unik');

        $this->info('Migrasi data kavling_peta...');
        $total = 0;

        DB::table('kavling_peta')->orderBy('id')->chunk(100, function ($kavlings) use ($komponen, &$total) {
            foreach ($kavlings as $k) {
                $rincian = [];

                $kolomMap = ['hrg_jual' => 'harga_jual', 'biaya_surat' => 'biaya_surat', 'peningkatan_mutu' => 'peningkatan_mutu'];
                foreach ($kolomMap as $kolom => $kode) {
                    $nilai = (int) ($k->$kolom ?? 0);
                    if ($nilai > 0 && isset($komponen[$kode])) {
                        $rincian[] = [
                            'id'    => $komponen[$kode]->id,
                            'kode'  => $kode,
                            'nilai' => $nilai,
                        ];
                    }
                }

                if (!empty($rincian)) {
                    DB::table('kavling_peta')
                        ->where('id', $k->id)
                        ->update(['rincian_biaya' => json_encode($rincian)]);
                    $total++;
                }
            }
        });

        $this->info("{$total} kavling berhasil dimigrasi.");

        $this->info('Backfill id_komponen_biaya di piutang...');
        $piutangTotal = 0;

        $keywordMap = [
            'Harga Rumah'         => 'harga_jual',
            'Biaya Surat'         => 'biaya_surat',
            'Peningkatan Mutu'    => 'peningkatan_mutu',
        ];

        foreach ($keywordMap as $keyword => $kode) {
            $compId = $komponen[$kode]->id ?? null;
            if (!$compId) continue;

            $updated = DB::table('piutang')
                ->whereNull('id_komponen_biaya')
                ->where('deskripsi', 'like', "%{$keyword}%")
                ->update(['id_komponen_biaya' => $compId]);

            $piutangTotal += $updated;
        }

        $this->info("{$piutangTotal} piutang berhasil di-backfill id_komponen_biaya.");

        return Command::SUCCESS;
    }
}
