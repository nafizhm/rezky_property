<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:reset-app', function () {
    $tables = DB::select('SHOW TABLES');
    $dbName = DB::getDatabaseName();
    $key = "Tables_in_{$dbName}";

    $keep = ['users', 'konfigurasi_media', 'konfigurasi', 'hak_akses', 'menu', 'role', 'role_user'];

    $toTruncate = [];
    foreach ($tables as $table) {
        $name = $table->$key;
        if (!in_array($name, $keep)) {
            $toTruncate[] = $name;
        }
    }

    if (empty($toTruncate)) {
        $this->warn('Tidak ada tabel yang perlu di-truncate.');
        return;
    }

    $this->info('Tabel yang AKAN di-truncate (' . count($toTruncate) . ' tabel):');
    foreach ($toTruncate as $t) {
        $this->line("  - {$t}");
    }

    if (!$this->confirm('Lanjutkan truncate?')) {
        $this->warn('Dibatalkan.');
        return;
    }

    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    foreach ($toTruncate as $name) {
        DB::statement("TRUNCATE TABLE `{$name}`");
        $this->info("  OK: {$name}");
    }
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    $this->info('Selesai! Semua tabel berhasil di-reset.');
})->purpose('Reset semua tabel aplikasi (kecuali users, konfigurasi, permission)');
