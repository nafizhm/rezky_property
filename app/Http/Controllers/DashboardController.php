<?php
namespace App\Http\Controllers;

use App\Models\BankKPR;
use App\Models\Customer;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\MarketingOffline;
use App\Models\ProgresListPenjualan;
use App\Models\Wawancara;
use App\Models\WawancaraSp3k;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->withError('Silahkan Login terlebih dahulu');
        }

        Carbon::setLocale('id');
        $tglSekarang   = Carbon::now()->translatedFormat('j F Y');
        $totalKavling  = KavlingPeta::count();
        $totalCustomer = Customer::count();
        $merah        = Customer::where('id_status_progres', 2)->count();
        $hijau         = Customer::where('id_status_progres', 7)->count();
        $ungu           = Customer::where('id_status_progres', 3)->count();

        $kolomStatus = ProgresListPenjualan::where('stt_tampil', 1)
            ->where('id', '!=', 8)
            ->orderBy('urutan', 'asc')
            ->get();

        $totalSemua = [
            'jumlah'         => 0,
            'kpr'            => 0,
            'cash'           => 0,
            'kredit'         => 0,
            'total_customer' => $totalCustomer,
            'hold'           => 0,
        ];

        foreach ($kolomStatus as $status) {
            $key              = strtolower(str_replace(' ', '_', $status->short_name));
            $totalSemua[$key] = 0;
        }

        $dataLokasi = LokasiKavling::whereIn('stt_tampil', [1, 3])->get()->map(function ($lokasi) use ($kolomStatus, &$totalSemua) {

            $id = $lokasi->id;

            $data = [
                'id'           => $id,
                'nama'         => $lokasi->nama_kavling,
                'jumlah'       => KavlingPeta::where('id_lokasi', $id)->count(),
                'nama_singk_1' => $lokasi->nama_singkat,
                'kpr'          => Customer::where('id_lokasi', $id)->where('jenis_pembelian', 'KPR')->count(),
                'cash'         => Customer::where('id_lokasi', $id)->where('jenis_pembelian', 'Pembelian Cash')->count(),
                'kredit'       => Customer::where('id_lokasi', $id)->where('jenis_pembelian', 'Cash Bertahap')->count(),
                'hold'         => KavlingPeta::where('id_lokasi', $id)->where('status', 1)->count(),
            ];

            $totalSemua['jumlah'] += $data['jumlah'];
            $totalSemua['kpr'] += $data['kpr'];
            $totalSemua['cash'] += $data['cash'];
            $totalSemua['kredit'] += $data['kredit'];
            $totalSemua['hold'] += $data['hold'];

            foreach ($kolomStatus as $status) {
                $key = strtolower(str_replace(' ', '_', $status->short_name));

                if ($status->id == 1) {
                    $data[$key] = KavlingPeta::where('id_lokasi', $id)->where('status', 0)->count();
                } else {
                    $data[$key] = Customer::where('id_lokasi', $id)
                        ->where('id_status_progres', $status->id)
                        ->count();
                }

                $totalSemua[$key] += $data[$key];
            }

            return $data;
        });

        $progresList = ProgresListPenjualan::where('id', '!=', 1)->get();
        $dataProgres = [];
        $noProgres   = 1;

        foreach ($progresList as $progres) {
            $jumlah     = Customer::where('id_status_progres', $progres->id)->count();
            $persentase = $totalCustomer > 0 ? round(($jumlah / $totalCustomer) * 100) : 0;

            $dataProgres[] = [
                'no'                => $noProgres++,
                'status_progres'    => $progres->status_progres,
                'jumlah'            => $jumlah,
                'persentase'        => $persentase,
                'id_status_progres' => $progres->id,
            ];
        }

        $bankList = BankKPR::all();
        $dataBank = [];
        $noBank   = 1;

        $totalWawancara = WawancaraSp3k::where('status', 1)->count();

        foreach ($bankList as $bank) {

            $jumlah = WawancaraSp3k::where('status', 1)
                ->where('id_bank_kpr', $bank->id)
                ->count();

            if ($jumlah <= 0) {
                continue;
            }

            $persentase = $totalWawancara > 0
                ? round(($jumlah / $totalWawancara) * 100)
                : 0;

            $dataBank[] = [
                'no'         => $noBank++,
                'bank'       => $bank->nama,
                'jumlah'     => $jumlah,
                'persentase' => $persentase,
                'id_bank'    => $bank->id,
            ];
        }

        $marketingList = MarketingOffline::all();
        $dataMarketing = [];
        $noMarketing   = 1;

        foreach ($marketingList as $marketing) {
            $jumlah     = Customer::where('id_marketing', $marketing->id)->count();
            $persentase = $totalCustomer > 0 ? round(($jumlah / $totalCustomer) * 100) : 0;

            $dataMarketing[] = [
                'no'           => $noMarketing++,
                'marketing'    => $marketing->nama_marketing,
                'jumlah'       => $jumlah,
                'persentase'   => $persentase,
                'id_marketing' => $marketing->id,
            ];
        }

        return view('admin.dashboard.dashboard', compact(
            'totalKavling',
            'tglSekarang',
            'dataLokasi',
            'dataProgres',
            'dataBank',
            'dataMarketing',
            'ungu',
            'merah',
            'hijau',
            'kolomStatus',
            'totalSemua'
        ));
    }

    public function showLokasiPenjualan(string $id)
    {
        $getName  = LokasiKavling::find($id);
        $viewData = [
            'lokasi_id' => $id,
            'scope'     => 'Lokasi Kavling',
            'nama'      => $getName->nama_kavling,
        ];

        $query = KavlingPeta::with(['customer.progres', 'customer.marketing'])
            ->withCount('customer')
            ->where('id_lokasi', $id)
            ->orderByRaw("SUBSTRING_INDEX(kode_kavling, '-', 1), CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_kavling, '-', -1), 'A', 1) AS UNSIGNED), kode_kavling");

        if (request()->ajax()) {
            return DataTables::of($query->get())
                ->addIndexColumn()
                ->addColumn('id_status_progres', function ($row) {
                    return optional(optional($row->customer)->progres)->status_progres ?? '';
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return optional($row->customer)->nama_lengkap ?? '';
                })
                ->addColumn('id_marketing', function ($row) {
                    return optional(optional($row->customer)->marketing)->nama_marketing ?? '';
                })
                ->make(true);
        }

        return view('admin.dashboard.statistik.lokasi', $viewData);
    }

    public function showCustomer(string $id)
    {
        $routeName = request()->route()->getName();
        $getName   = [];
        $viewData  = [];

        if ($routeName === 'dashboard.customer-status-progres-show') {
            $query    = Customer::where('id_status_progres', $id);
            $getName  = ProgresListPenjualan::find($id);
            $viewData = [
                'status_progres_id' => $id,
                'scope'             => 'Progres Penjualan',
                'nama'              => $getName->status_progres ?? '-',
            ];
        } elseif ($routeName === 'dashboard.customer-bank-show') {

            $idWawancara = Wawancara::where('status', 2)
                ->pluck('id');

            $query = Customer::whereHas('wawancara', function ($q) use ($idWawancara, $id) {
                $q->whereIn('wawancara.id', $idWawancara)
                    ->whereHas('wawancaraSp3k', function ($qq) use ($id) {
                        $qq->where('wawancara_sp3k.status', 1)
                            ->where('wawancara_sp3k.id_bank_kpr', $id);
                    });
            });

            $getName = BankKPR::find($id);

            $viewData = [
                'bank_id' => $id,
                'scope'   => 'Bank KPR',
                'nama'    => $getName->nama ?? '-',
            ];
        } elseif ($routeName === 'dashboard.customer-marketing-show') {
            $query    = Customer::where('id_marketing', $id);
            $getName  = MarketingOffline::find($id);
            $viewData = [
                'marketing_id' => $id,
                'scope'        => 'Marketing',
                'nama'         => $getName->nama_marketing ?? '-',
            ];
        }

        if (request()->ajax()) {
            Carbon::setLocale('id');
            $program = $query->with(['marketing', 'lokasi', 'kavling', 'progres']);

            return DataTables::of($program)
                ->addIndexColumn()

                ->editColumn('tanggal_verif', function ($row) {
                    $tgl  = $row->tanggal_verif ? Carbon::parse($row->tanggal_verif)->translatedFormat('d F Y') : '-';
                    $kode = $row->kode_customer ? '<strong>' . $row->kode_customer . '</strong>' : '';
                    return "$tgl<br>$kode";
                })

                ->editColumn('id_marketing', function ($row) {
                    return $row->marketing->nama_marketing ?? '<span class="badge bg-danger">None Marketing</span>';
                })

                ->editColumn('id_lokasi', function ($row) {
                    $namaLokasi  = $row->lokasi->nama_kavling ?? '-';
                    $kodeKavling = $row->kavling->kode_kavling ?? '-';
                    return '<strong>' . $namaLokasi . '</strong><br>' . $kodeKavling;
                })

                ->editColumn('id_status_progres', function ($row) {
                    $status      = $row->progres->status_progres ?? '-';
                    $ketCashback = $row->progres->ket_cashback ?? '';
                    $badgeColors = [
                        'BOOKING FEE'  => 'warning',
                        'SP3K'         => 'success',
                        'AKAD'         => 'info',
                        'SERAH TERIMA' => 'dark',
                    ];
                    $statusDisplay = isset($badgeColors[$status])
                        ? '<span class="badge bg-' . $badgeColors[$status] . '">' . $status . '</span>'
                        : $status;
                    $cashbackText = $ketCashback ? '<br><small>' . $ketCashback . '</small>' : '';
                    return $statusDisplay . $cashbackText;
                })

                ->editColumn('nama_lengkap', function ($row) {
                    $nama = '<strong>' . $row->nama_lengkap . '</strong>';
                    $wa   = $row->no_telp ?? '-';
                    $ktp  = $row->nik ? '<span class="badge bg-info">NIK: ' . $row->nik . '</span>' : '';
                    return "$nama<br>$wa<br>$ktp";
                })
                ->rawColumns([
                    'tanggal_verif',
                    'nama_lengkap',
                    'id_marketing',
                    'id_lokasi',
                    'id_status_progres',
                ])
                ->make(true);
        }

        return view('admin.dashboard.statistik.customer', $viewData);
    }

    public function totalUnit(Request $request)
    {
        if ($request->ajax()) {
            $data = KavlingPeta::select(
                'kavling_peta.*',
                'lokasi_kavling.nama_kavling as nama_cluster'
            )
                ->leftJoin('lokasi_kavling', 'kavling_peta.id_lokasi', '=', 'lokasi_kavling.id');

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('panjang', function ($row) {
                    return '
                        <p>pjg kanan: <strong>' . $row->panjang_kanan . ' m</strong></p>
                        <p>pjg kiri: <strong>' . $row->panjang_kiri . ' m</strong></p>
                    ';
                })
                ->addColumn('lebar', function ($row) {
                    return '
                        <p>lebar depan: <strong>' . $row->lebar_depan . ' m</strong></p>
                        <p>lebar belakang: <strong>' . $row->lebar_belakang . ' m</strong></p>
                    ';
                })
                ->addColumn('luas', function ($row) {
                    return '
                        <p>luas tanah: <strong>' . $row->luas_tanah . ' m</strong></p>
                        <p>luas bangunan: <strong>' . $row->luas_bangunan . ' m</strong></p>
                    ';
                })
                ->addColumn('harga', function ($row) {
                    return number_format($row->hrg_jual, 0, ',', '.');
                })
                ->addColumn('lokasi', function ($row) {
                    return $row->kode_kavling;
                })
                ->rawColumns(['panjang', 'lebar', 'luas'])
                ->make(true);
        }

        return view('admin.dashboard.TotalUnit');
    }

    public function booking(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::with(['lokasi', 'progres'])
                ->whereIn('id_status_progres', [1, 22]);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('id_lokasi', function ($row) {
                    return $row->lokasi ? $row->lokasi->nama_kavling : '-';
                })
                ->addColumn('id_status_progres', function ($row) {
                    return $row->progres ? $row->progres->status_progres : '-';
                })
                ->make(true);
        }

        return view('admin.dashboard.Booking');
    }

    public function wawancara(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::with(['lokasi', 'progres'])
                ->whereIn('id_status_progres', [18]);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('id_lokasi', function ($row) {
                    return $row->lokasi ? $row->lokasi->nama_kavling : '-';
                })
                ->addColumn('id_status_progres', function ($row) {
                    return $row->progres ? $row->progres->status_progres : '-';
                })
                ->make(true);
        }

        return view('admin.dashboard.Wawancara');
    }
    public function akad(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::with(['lokasi', 'progres'])
                ->whereIn('id_status_progres', [3]);

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('id_lokasi', function ($row) {
                    return $row->lokasi ? $row->lokasi->nama_kavling : '-';
                })
                ->addColumn('id_status_progres', function ($row) {
                    return $row->progres ? $row->progres->status_progres : '-';
                })
                ->make(true);
        }

        return view('admin.dashboard.Akad');
    }

}
