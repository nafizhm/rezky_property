<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait LogAktivitasTrait
{
    /**
     * Log aktivitas login pengguna
     */
    protected function logLogin(): void
    {
        $this->storeLog('login');
    }

    /**
     * Log aktivitas logout pengguna
     */
    protected function logLogout(): void
    {
        $this->storeLog('logout');
    }

    /**
     * Log aktivitas create data
     *
     * @param  string  $namaMenu  Nama menu/modul tempat data dibuat
     * @param  int|string  $idData  ID data yang dibuat
     */
    protected function logCreate(string $namaMenu, $idData): void
    {
        $this->storeLogCRD('create', $namaMenu, $idData);
    }

    /**
     * Log aktivitas edit data
     *
     * @param  string  $namaMenu  Nama menu/modul tempat data diedit
     * @param  int|string  $idData  ID data yang diedit
     */
    protected function logEdit(string $namaMenu, $idData): void
    {
        $this->storeLogCRD('edit', $namaMenu, $idData);
    }

    /**
     * Log aktivitas delete data
     *
     * @param  string  $namaMenu  Nama menu/modul tempat data dihapus
     * @param  int|string  $idData  ID data yang dihapus
     */
    protected function logDelete(string $namaMenu, $idData): void
    {
        $this->storeLogCRD('delete', $namaMenu, $idData);
    }

    /**
     * Store log aktivitas sederhana (login/logout)
     *
     * @param  string  $aktivitas  Jenis aktivitas (login/logout)
     */
    private function storeLog(string $aktivitas): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $now = Carbon::now();
        $tanggal = $now->translatedFormat('d F Y');
        $jam = $now->format('H:i');

        $pesan = "User {$user->username} dengan id {$user->id} melakukan {$aktivitas} pada tanggal {$tanggal} jam {$jam}";

        LogAktivitas::create([
            'id_user' => $user->id,
            'user_name' => $user->username,
            'aktivitas' => $pesan,
        ]);
    }

    /**
     * Store log aktivitas CRD (Create/Edit/Delete)
     *
     * @param  string  $aksi  Jenis aksi (create/edit/delete)
     * @param  string  $namaMenu  Nama menu/modul
     * @param  int|string  $idData  ID data yang diproses
     */
    private function storeLogCRD(string $aksi, string $namaMenu, $idData): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $now = Carbon::now();
        $tanggal = $now->translatedFormat('d F Y');
        $jam = $now->format('H:i');

        $pesan = "User {$user->username} dengan id {$user->id} pada tanggal {$tanggal} jam {$jam} di menu {$namaMenu} melakukan {$aksi} data dengan id {$idData}";

        LogAktivitas::create([
            'id_user' => $user->id,
            'user_name' => $user->username,
            'aktivitas' => $pesan,
        ]);
    }
}
