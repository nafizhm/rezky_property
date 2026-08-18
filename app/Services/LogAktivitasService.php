<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class LogAktivitasService
{
    /**
     * Get DataTable of log aktivitas
     */
    public function getDataTable(?string $startDate = null, ?string $endDate = null)
    {
        $data = LogAktivitas::query()->with('user')->orderBy('id', 'desc');

        // Filter by date range
        if ($startDate) {
            $data->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $data->whereDate('created_at', '<=', $endDate);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('user_name', fn($row) => $row->user->username ?? '-')
            ->addColumn('tanggal', function ($row) {
                return $row->created_at 
                    ? Carbon::parse($row->created_at)->translatedFormat('d F Y')
                    : '-';
            })
            ->addColumn('jam', function ($row) {
                return $row->created_at 
                    ? Carbon::parse($row->created_at)->format('H:i')
                    : '-';
            })
            ->rawColumns([])
            ->make(true);
    }
}
