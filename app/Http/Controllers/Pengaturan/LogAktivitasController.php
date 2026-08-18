<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Services\LogAktivitasService;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    protected $logAktivitasService;

    public function __construct(LogAktivitasService $logAktivitasService)
    {
        $this->logAktivitasService = $logAktivitasService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->logAktivitasService->getDataTable(
                $request->input('start_date'),
                $request->input('end_date')
            );
        }

        return view('admin.pengaturan.log_aktivitas.index');
    }
}
