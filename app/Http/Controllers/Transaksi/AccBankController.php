<?php
namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Wawancara;
use App\Models\WawancaraSp3k;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

Carbon::setLocale('id');

class AccBankController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        app(AkadController::class)->refreshDeadlineAkad();

        if ($request->ajax()) {
            $data = WawancaraSp3k::with(
                'wawancara.customer',
                'wawancara.customer.lokasi',
                'wawancara.customer.kavling',
                'bankKPR'
            )
                ->where('status', 1)
                ->whereHas('wawancara.customer', function ($q) {
                    $q->where('stt_arsip', 0);
                })
                ->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('lokasi_rumah', function ($row) {
                    $kode = $row->wawancara->customer->kavling->kode_kavling ?? '-';
                    $nama = $row->wawancara->customer->lokasi->nama_kavling ?? '-';
                    return "$kode - $nama";
                })
                ->addColumn('bankKPR', function ($row) {
                    return optional($row->bankKPR)->nama ?? '-';
                })
                ->editColumn('harga_jual', function ($row) {
                    return '
                    <div class="d-flex justify-content-between harga-format w-100">
                        <span>Rp.</span>
                        <span>' . number_format($row->wawancara->customer->kavling->hrg_jual, 0, ',', '.') . '</span>
                    </div>';
                })
                ->editColumn('acc_plafon', function ($row) {
                    return '
                    <div class="d-flex justify-content-between harga-format w-100">
                        <span>Rp.</span>
                        <span>' . number_format($row->acc_plafon, 0, ',', '.') . '</span>
                    </div>';
                })
                ->editColumn('tgl_terbit_sp3k', function ($row) {
                    return $row->tgl_terbit_sp3k ? Carbon::parse($row->tgl_terbit_sp3k)->translatedFormat('d F Y') : '-';
                })

                ->editColumn('tgl_expired', function ($row) {
                    return $row->tgl_expired ? Carbon::parse($row->tgl_expired)->translatedFormat('d F Y') : '-';
                })

                ->addColumn('sisa_hari', function ($row) {
                    if ($row->tgl_terbit_sp3k && $row->tgl_expired) {
                        try {
                            $tglExpired = Carbon::parse($row->tgl_expired)->startOfDay();
                            $today      = Carbon::now()->startOfDay();

                            $sisaHari = $today->diffInDays($tglExpired, false);

                            return $sisaHari > 0
                                ? $sisaHari . ' hari'
                                : '<span class="text-danger">Kadaluarsa</span>';
                        } catch (\Exception $e) {
                            return '<span class="text-danger">Tanggal tidak valid</span>';
                        }
                    }

                    return '-';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('acc-bank.show', $row->id_wawancara);
                    $deleteUrl = route('acc-bank.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<a href="' . e($editUrl) . '" class="btn btn-primary btn-sm mx-1">Detail</a>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm">Batalkan</button></form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['action', 'harga_jual', 'acc_plafon', 'sisa_hari'])
                ->make(true);
        }

        return view('admin.transaksi.acc_bank.index', compact('permissions'));
    }

    public function show(Request $request, $id)
    {
        $data = Wawancara::with('customer', 'customer.lokasi', 'customer.kavling')->findOrFail($id);

        if ($request->ajax()) {
            $data = WawancaraSp3k::with('wawancara.customer', 'wawancara.customer.lokasi', 'wawancara.customer.kavling', 'wawancara.bankKPR')
                ->where('id_wawancara', $id)->orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('bankKPR', function ($row) {
                    return optional($row->bankKPR)->nama ?? '-';
                })
                ->editColumn('acc_plafon', function ($row) {
                    return '
                    <div class="d-flex justify-content-between harga-format w-100">
                        <span>Rp.</span>
                        <span>' . number_format($row->acc_plafon, 0, ',', '.') . '</span>
                    </div>';
                })
                ->editColumn('tgl_terbit_sp3k', function ($row) {
                    return $row->tgl_terbit_sp3k ? Carbon::parse($row->tgl_terbit_sp3k)->translatedFormat('d F Y') : '-';
                })

                ->editColumn('tgl_expired', function ($row) {
                    return $row->tgl_expired ? Carbon::parse($row->tgl_expired)->translatedFormat('d F Y') : '-';
                })

                ->addColumn('sisa_hari', function ($row) {
                    if ($row->tgl_terbit_sp3k && $row->tgl_expired) {
                        try {
                            $tglExpired = Carbon::parse($row->tgl_expired)->startOfDay();
                            $today      = Carbon::now()->startOfDay();

                            $sisaHari = $today->diffInDays($tglExpired, false);

                            return $sisaHari > 0
                                ? $sisaHari . ' hari'
                                : '<span class="text-danger">Kadaluarsa</span>';
                        } catch (\Exception $e) {
                            return '<span class="text-danger">Tanggal tidak valid</span>';
                        }
                    }

                    return '-';
                })
                ->rawColumns(['acc_plafon', 'sisa_hari'])
                ->make(true);
        }

        return view('admin.transaksi.acc_bank.detail', compact('data'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = WawancaraSp3k::with('wawancara', 'wawancara.customer')->findOrFail($id);

            $data->wawancara->update([
                'status' => 1,
            ]);

            $data->wawancara->customer->update([
                'id_status_progres' => 7,
            ]);

            $data->delete();

            $this->logDelete('Wawancara ACC BANK', $data->id);

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error batalkan SP3K : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
