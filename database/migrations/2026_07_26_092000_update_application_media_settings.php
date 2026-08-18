<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('konfigurasi_media')
            ->whereIn('jenis_data', ['Logo Rekap', 'Background Rekap'])
            ->update(['stt_aktif' => 0]);

        $logoLogin = DB::table('konfigurasi_media')
            ->where('jenis_data', 'Logo Login')
            ->first();

        if (! $logoLogin) {
            $logoLogin = DB::table('konfigurasi_media')
                ->where('jenis_data', 'Logo website')
                ->orWhere('jenis_data', 'logo website')
                ->first();

            if ($logoLogin) {
                DB::table('konfigurasi_media')
                    ->where('id', $logoLogin->id)
                    ->update([
                        'jenis_data' => 'Logo Login',
                        'keterangan' => 'Logo yang ditampilkan pada halaman login',
                        'urutan' => 1,
                        'stt_aktif' => 1,
                    ]);

                $logoLogin = DB::table('konfigurasi_media')->where('id', $logoLogin->id)->first();
            }
        }

        $logoAplikasi = DB::table('konfigurasi_media')
            ->where('jenis_data', 'Logo Aplikasi')
            ->first();

        if (! $logoAplikasi) {
            DB::table('konfigurasi_media')->insert([
                'jenis_data' => 'Logo Aplikasi',
                'keterangan' => 'Logo yang ditampilkan pada sidebar aplikasi setelah login',
                'nama_file' => $logoLogin->nama_file ?? 'default.png',
                'urutan' => 2,
                'jenis_download' => 0,
                'stt_aktif' => 1,
            ]);
        } else {
            DB::table('konfigurasi_media')
                ->where('id', $logoAplikasi->id)
                ->update([
                    'keterangan' => 'Logo yang ditampilkan pada sidebar aplikasi setelah login',
                    'urutan' => 2,
                    'stt_aktif' => 1,
                ]);
        }

        DB::table('konfigurasi_media')
            ->where('jenis_data', 'fav icon')
            ->update([
                'keterangan' => 'Icon kecil yang ditampilkan pada tab browser',
                'urutan' => 3,
                'stt_aktif' => 1,
            ]);

        DB::table('konfigurasi_media')
            ->where('jenis_data', 'Background booking')
            ->update(['urutan' => 4]);

        DB::table('konfigurasi_media')
            ->where('jenis_data', 'Background login')
            ->update(['urutan' => 5]);
    }

    public function down(): void
    {
        //
    }
};
