<?php

namespace App\Http\Controllers\Siteplan;

use App\Http\Controllers\Controller;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\ProgresListPenjualan;
use Illuminate\Http\Request;
use App\Models\PengaturanMedia;
use App\Models\Piutang;
use App\Models\Pemasukan;

class PublicSiteplanController extends Controller
{
    /**
     * Display the public siteplan.
     */
     public function index()
    {
        $lokasiKavling = LokasiKavling::with(['kavlingPeta.customer.progres', 'kavlingPeta.progres'])
            ->orderBy('urutan', 'asc')
            ->get();

        $legend = ProgresListPenjualan::whereNotNull('warna')
            ->where('warna', '!=', '')
            ->where('stt_tampil', 1)
            ->whereIn('status_progres', ['Ready', 'Booking Fee', 'Serah Terima'])
            ->orderBy('urutan', 'asc')
            ->get();

            $bg        = PengaturanMedia::where('jenis_data', 'Background booking')->first();

        return view('public_siteplan.index', compact('lokasiKavling', 'legend', 'bg'));
    }

    /**
     * Fetch kavling details for the public popup.
     */
     public function show($id)
    {
        $data = KavlingPeta::with(['lokasi', 'customer', 'listrikAir'])->findOrFail($id);

        $tagihanList   = Piutang::where('id_customer', $data->id_customer)->orderBy('id')->get();
        $pemasukanList = Pemasukan::with('kategori')->where('id_customer', $data->id_customer)->get();

        return response()->json([
            'success'         => true,
            'data'            => $data,
            'tagihan'         => $tagihanList,
            'listrik_air' => $data->listrikAir,
            'pemasukan'       => $pemasukanList,
            'total_tagihan'   => $tagihanList->sum('nominal'),
            'total_pemasukan' => $pemasukanList->sum('nominal'),
        ]);
    }
}
