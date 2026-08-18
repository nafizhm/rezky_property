<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\ArsipCustomer;
use App\Models\Customer;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ArsipCustomerController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();
        Carbon::setLocale('id');

        if ($request->ajax()) {
            $data = Customer::where('stt_arsip', 1)->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    $tgl = Carbon::parse($row->tanggal_verif)->translatedFormat('d F Y');
                    return "$tgl<br><strong>{$row->kode_customer}</strong>";
                })
                ->addColumn('marketing', function ($row) {
                    if ($row->id_marketing && $row->marketing) {
                        $namaMarketing = $row->marketing->nama_marketing;
                    } else {
                        $namaMarketing = null;
                    }

                    if (! $namaMarketing) {
                        return "<span class='badge bg-danger'>Non Marketing</span>";
                    }

                    return $namaMarketing;
                })

                ->addColumn('lokasi', function ($row) {
                    $lokasi  = $row->lokasiKavling ? $row->lokasiKavling->nama_kavling : '-';
                    $kavling = $row->kavlingPeta ? $row->kavlingPeta->kode_kavling : '-';
                    return "<strong style='font-size: 1.1rem'>$lokasi</strong><br>$kavling";
                })
                ->addColumn('status', function ($row) {
                    $status     = $row->progres ? $row->progres->status_progres : '-';
                    $badgeClass = match (strtolower($status)) {
                        'booking fee' => 'bg-warning text-dark',
                        'WAWANCARA'    => 'bg-secondarry',
                        'akad'         => 'bg-info text-dark',
                        'serah terima' => 'bg-dark',
                        'soldout'      => 'bg-danger',
                        'sp3k'         => 'bg-success',
                        default        => ''
                    };

                    if ($badgeClass) {
                        return "<span class='badge $badgeClass'>" . ucfirst($status) . "</span>";
                    }

                    return $status;
                })

                ->addColumn('action', function ($row) use ($permissions) {
                    $deleteUrl = route('arsip-customer.destroy', $row->id);
                    $btn       = '<div class="text-center">';

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="delete-button btn btn-danger btn-sm">
                                Kembalikan
                            </button>
                         </form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['tanggal', 'customer', 'marketing', 'lokasi', 'status', 'action'])
                ->make(true);

        }

        return view('admin.customer.arsip_customer.index', compact('permissions'));
    }
    
    public function destroy($id)
    {
        $data = ArsipCustomer::findOrFail($id);
        $this->logDelete('Arsip Customer', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }

}
