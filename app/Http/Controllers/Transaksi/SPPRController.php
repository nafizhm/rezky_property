<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Customer;
use App\Models\MarketingOffline;
use App\Models\SPPR;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SPPRController extends Controller
{
    use LogAktivitasTrait;

    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = SPPR::with('customer')->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_nama', function ($row) {
                    return $row->nama;
                })
                ->addColumn('customer_lokasi', function ($row) {
                    return $row->blok . ' - ' . $row->no;
                })
                ->addColumn('total_format', function ($row) {
                    return 'Rp ' . number_format($row->total_yang_harus_dibayar, 0, ',', '.');
                })
                ->addColumn('cicilan_format', function ($row) {
                    return 'Rp ' . number_format($row->cicilan_per_bulan, 0, ',', '.');
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $cetakUrl = route('sppr.cetak', $row->id);
                    $editUrl = route('sppr.edit', $row->id);
                    $deleteUrl = route('sppr.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-info btn-sm mx-1 edit-button"
                                data-id="' . e($row->id) . '"
                                data-url="' . e($editUrl) . '">Edit</button>';
                    }
                    $btn .= '<a href="' . e($cetakUrl) . '" target="_blank" class="btn btn-dark btn-sm mx-1">Cetak</a>';
                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="delete-button btn btn-danger btn-sm mx-1">Hapus</button>
                        </form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $customerList = Customer::orderBy('nama_lengkap')->get();
        $marketingList = MarketingOffline::orderBy('nama_marketing')->get();

        $lastId = SPPR::max('id') ?? 0;
        $nextNoSppr = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        return view('admin.transaksi.sppr.index', compact('permissions', 'customerList', 'marketingList', 'nextNoSppr'));
    }

    public function getCustomerDetail($id)
    {
        try {
            $customer = Customer::with(['lokasi', 'kavling', 'pemasukans', 'marketing'])->findOrFail($id);

            $bookingFee = (int) $customer->pemasukans()
                ->where('id_kategori_transaksi', 1)
                ->sum('nominal');

            $blok = '';
            $no = '';
            if ($customer->kavling) {
                if ($customer->lokasi && $customer->lokasi->is_cluster) {
                    $blok = $customer->kavling->cluster ?? '';
                    $no = $customer->kavling->no ?? '';
                } else {
                    $kode = $customer->kavling->kode_kavling ?? '';
                    $parts = explode('-', $kode);
                    $blok = $parts[0] ?? $kode;
                    $no = $parts[1] ?? '';
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'nama_lengkap' => $customer->nama_lengkap,
                    'alamat' => $customer->alamat_ktp ?? $customer->alamat_domisili ?? '',
                    'nik' => $customer->nik,
                    'no_telp' => $customer->no_telp,
                    'id_marketing' => $customer->id_marketing,
                    'nama_marketing' => $customer->marketing->nama_marketing ?? '',
                    'luas_bangunan' => $customer->kavling->luas_bangunan ?? 0,
                    'luas_tanah' => $customer->kavling->luas_tanah ?? 0,
                    'blok' => $blok,
                    'no' => $no,
                    'harga_jual' => $customer->hrg_jual ?? 0,
                    'biaya_surat_surat' => $customer->biaya_surat ?? 0,
                    'peningkatan_mutu' => $customer->peningkatan_mutu ?? 0,
                    'jumlah_booking_fee' => $bookingFee,
                    'pekerjaan' => $customer->pekerjaan ?? '',
                    'agama' => $customer->agama ?? '',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data customer tidak ditemukan.'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required|integer|exists:customer,id',
            'no_sppr' => 'nullable',
            'nama' => 'required',
            'alamat' => 'required',
            'nik' => 'required',
            'no_telp' => 'required',
            'luas_bangunan' => 'required|numeric',
            'luas_tanah' => 'required|numeric',
            'blok' => 'required',
            'no' => 'required',
            'harga_jual' => 'required|numeric',
            'asumsi_plafon_kpr' => 'required|numeric',
            'biaya_surat_surat' => 'required|numeric',
            'peningkatan_mutu' => 'required|numeric',
            'biaya_kelebihan_tanah' => 'nullable|numeric',
            'biaya_sudut' => 'nullable|numeric',
            'biaya_lain_lain' => 'nullable|numeric',
            'jumlah_booking_fee' => 'required|numeric',
            'cicilan_per_bulan' => 'required|numeric',
            'id_marketing' => 'nullable|integer',
            'penandatangan' => 'nullable',
            'keterangan' => 'nullable',
            'agama' => 'nullable',
            'pekerjaan' => 'nullable',
            'promo' => 'nullable',
            'perubahan_posisi' => 'nullable',
            'keterangan_booking' => 'nullable',
            'nominal_dp' => 'nullable|numeric',
            'keterangan_dp' => 'nullable',
            'nominal_biaya_posisi_unit' => 'nullable|numeric',
            'keterangan_posisi_unit' => 'nullable',
            'nominal_biaya_kpr' => 'nullable|numeric',
            'keterangan_kpr' => 'nullable',
            'nominal_blokir_angsuran' => 'nullable|numeric',
            'keterangan_blokir_angsuran' => 'nullable',
            'nominal_biaya_materai' => 'nullable|numeric',
            'keterangan_materai' => 'nullable',
            'nominal_biaya_buka_tabungan' => 'nullable|numeric',
            'keterangan_tabungan' => 'nullable',
            'keterangan_shm' => 'nullable',
        ], [
            'id_customer.required' => 'Customer wajib dipilih.',
            'nama.required' => 'Nama wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'no_telp.required' => 'No Telp wajib diisi.',
            'luas_bangunan.required' => 'Luas Bangunan wajib diisi.',
            'luas_tanah.required' => 'Luas Tanah wajib diisi.',
            'blok.required' => 'Blok wajib diisi.',
            'no.required' => 'No wajib diisi.',
            'harga_jual.required' => 'Harga Jual wajib diisi.',
            'asumsi_plafon_kpr.required' => 'Asumsi Plafon KPR wajib diisi.',
            'biaya_surat_surat.required' => 'Biaya Surat-surat wajib diisi.',
            'peningkatan_mutu.required' => 'Peningkatan Mutu wajib diisi.',
            'jumlah_booking_fee.required' => 'Jumlah Booking Fee wajib diisi.',
            'cicilan_per_bulan.required' => 'Cicilan per Bulan wajib diisi.',
        ]);

        $total = $this->hitungTotal($request);

        $sppr = SPPR::create([
            'id_customer' => $request->id_customer,
            'no_sppr' => $request->no_sppr,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nik' => $request->nik,
            'no_telp' => $request->no_telp,
            'luas_bangunan' => $request->luas_bangunan,
            'luas_tanah' => $request->luas_tanah,
            'blok' => $request->blok,
            'no' => $request->no,
            'harga_jual' => $request->harga_jual,
            'asumsi_plafon_kpr' => $request->asumsi_plafon_kpr,
            'biaya_surat_surat' => $request->biaya_surat_surat,
            'peningkatan_mutu' => $request->peningkatan_mutu,
            'biaya_kelebihan_tanah' => $request->biaya_kelebihan_tanah,
            'biaya_sudut' => $request->biaya_sudut,
            'biaya_lain_lain' => $request->biaya_lain_lain,
            'total_yang_harus_dibayar' => $total,
            'jumlah_booking_fee' => $request->jumlah_booking_fee,
            'cicilan_per_bulan' => $request->cicilan_per_bulan,
            'id_marketing' => $request->id_marketing,
            'penandatangan' => $request->penandatangan,
            'keterangan' => $request->keterangan,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'promo' => $request->promo,
            'perubahan_posisi' => $request->perubahan_posisi,
            'keterangan_booking' => $request->keterangan_booking,
            'nominal_dp' => $request->nominal_dp,
            'keterangan_dp' => $request->keterangan_dp,
            'nominal_biaya_posisi_unit' => $request->nominal_biaya_posisi_unit,
            'keterangan_posisi_unit' => $request->keterangan_posisi_unit,
            'nominal_biaya_kpr' => $request->nominal_biaya_kpr,
            'keterangan_kpr' => $request->keterangan_kpr,
            'nominal_blokir_angsuran' => $request->nominal_blokir_angsuran,
            'keterangan_blokir_angsuran' => $request->keterangan_blokir_angsuran,
            'nominal_biaya_materai' => $request->nominal_biaya_materai,
            'keterangan_materai' => $request->keterangan_materai,
            'nominal_biaya_buka_tabungan' => $request->nominal_biaya_buka_tabungan,
            'keterangan_tabungan' => $request->keterangan_tabungan,
            'keterangan_shm' => $request->keterangan_shm,
        ]);

        $this->logCreate('SPPR', $sppr->id);

        return response()->json(['status' => 'success']);
    }

    public function edit($id)
    {
        $sppr = SPPR::with('customer')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $sppr,
        ]);
    }

    public function update(Request $request, $id)
    {
        $sppr = SPPR::findOrFail($id);

        $request->validate([
            'id_customer' => 'required|integer|exists:customer,id',
            'no_sppr' => 'nullable',
            'nama' => 'required',
            'alamat' => 'required',
            'nik' => 'required',
            'no_telp' => 'required',
            'luas_bangunan' => 'required|numeric',
            'luas_tanah' => 'required|numeric',
            'blok' => 'required',
            'no' => 'required',
            'harga_jual' => 'required|numeric',
            'asumsi_plafon_kpr' => 'required|numeric',
            'biaya_surat_surat' => 'required|numeric',
            'peningkatan_mutu' => 'required|numeric',
            'biaya_kelebihan_tanah' => 'nullable|numeric',
            'biaya_sudut' => 'nullable|numeric',
            'biaya_lain_lain' => 'nullable|numeric',
            'jumlah_booking_fee' => 'required|numeric',
            'cicilan_per_bulan' => 'required|numeric',
            'id_marketing' => 'nullable|integer',
            'penandatangan' => 'nullable',
            'keterangan' => 'nullable',
            'agama' => 'nullable',
            'pekerjaan' => 'nullable',
            'promo' => 'nullable',
            'perubahan_posisi' => 'nullable',
            'keterangan_booking' => 'nullable',
            'nominal_dp' => 'nullable|numeric',
            'keterangan_dp' => 'nullable',
            'nominal_biaya_posisi_unit' => 'nullable|numeric',
            'keterangan_posisi_unit' => 'nullable',
            'nominal_biaya_kpr' => 'nullable|numeric',
            'keterangan_kpr' => 'nullable',
            'nominal_blokir_angsuran' => 'nullable|numeric',
            'keterangan_blokir_angsuran' => 'nullable',
            'nominal_biaya_materai' => 'nullable|numeric',
            'keterangan_materai' => 'nullable',
            'nominal_biaya_buka_tabungan' => 'nullable|numeric',
            'keterangan_tabungan' => 'nullable',
            'keterangan_shm' => 'nullable',
        ]);

        $total = $this->hitungTotal($request);

        $sppr->update([
            'id_customer' => $request->id_customer,
            'no_sppr' => $request->no_sppr,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nik' => $request->nik,
            'no_telp' => $request->no_telp,
            'luas_bangunan' => $request->luas_bangunan,
            'luas_tanah' => $request->luas_tanah,
            'blok' => $request->blok,
            'no' => $request->no,
            'harga_jual' => $request->harga_jual,
            'asumsi_plafon_kpr' => $request->asumsi_plafon_kpr,
            'biaya_surat_surat' => $request->biaya_surat_surat,
            'peningkatan_mutu' => $request->peningkatan_mutu,
            'biaya_kelebihan_tanah' => $request->biaya_kelebihan_tanah,
            'biaya_sudut' => $request->biaya_sudut,
            'biaya_lain_lain' => $request->biaya_lain_lain,
            'total_yang_harus_dibayar' => $total,
            'jumlah_booking_fee' => $request->jumlah_booking_fee,
            'cicilan_per_bulan' => $request->cicilan_per_bulan,
            'id_marketing' => $request->id_marketing,
            'penandatangan' => $request->penandatangan,
            'keterangan' => $request->keterangan,
            'agama' => $request->agama,
            'pekerjaan' => $request->pekerjaan,
            'promo' => $request->promo,
            'perubahan_posisi' => $request->perubahan_posisi,
            'keterangan_booking' => $request->keterangan_booking,
            'nominal_dp' => $request->nominal_dp,
            'keterangan_dp' => $request->keterangan_dp,
            'nominal_biaya_posisi_unit' => $request->nominal_biaya_posisi_unit,
            'keterangan_posisi_unit' => $request->keterangan_posisi_unit,
            'nominal_biaya_kpr' => $request->nominal_biaya_kpr,
            'keterangan_kpr' => $request->keterangan_kpr,
            'nominal_blokir_angsuran' => $request->nominal_blokir_angsuran,
            'keterangan_blokir_angsuran' => $request->keterangan_blokir_angsuran,
            'nominal_biaya_materai' => $request->nominal_biaya_materai,
            'keterangan_materai' => $request->keterangan_materai,
            'nominal_biaya_buka_tabungan' => $request->nominal_biaya_buka_tabungan,
            'keterangan_tabungan' => $request->keterangan_tabungan,
            'keterangan_shm' => $request->keterangan_shm,
        ]);

        $this->logEdit('SPPR', $sppr->id);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $sppr = SPPR::findOrFail($id);

        $this->logDelete('SPPR', $sppr->id);
        $sppr->delete();

        return response()->json(['status' => 'success']);
    }


    public function cetak($id)
    {
        $sppr = SPPR::with('customer.lokasi', 'customer.kavling', 'customer.pemasukans', 'customer.marketing', 'marketing')->findOrFail($id);
        $customer = $sppr->customer;
        $namaPerum = $customer->lokasi->nama_kavling ?? '-';
        $lokasiPerum = $customer->lokasi->alamat ?? '-';

        $templatePath = public_path('templates/template_sppr/template_sppr.docx');

        if (!file_exists($templatePath)) {
            abort(404, 'Template SPPR tidak ditemukan.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $tanggalDoc = $sppr->created_at ? Carbon::parse($sppr->created_at) : Carbon::now();

        $fmt = function ($val) {
            return number_format((int) ($val ?? 0), 0, ',', '.');
        };

        $templateProcessor->setValues([
            'tanggal'                    => $tanggalDoc->format('d'),
            'bulan'                      => $tanggalDoc->locale('id')->isoFormat('MMMM'),
            'tahun'                      => $tanggalDoc->format('Y'),
            'hari'                       => $tanggalDoc->locale('id')->isoFormat('dddd'),
            'nama_lengkap'               => $sppr->nama,
            'nik'                        => $sppr->nik,
            'alamat'                     => $sppr->alamat,
            'agama'                      => $sppr->agama ?? '-',
            'pekerjaan'                  => $sppr->pekerjaan ?? $customer->pekerjaan ?? '-',
            'no_telp'                    => $sppr->no_telp,
            'blok'                       => $sppr->blok,
            'no_unit'                    => $sppr->no,
            'luas_tanah'                 => (string) ($sppr->luas_tanah ?? 0),
            'biaya_kelebihan_tanah'      => $fmt($sppr->biaya_kelebihan_tanah),
            'promo'                      => $sppr->promo ?? '-',
            'perubahan_posisi'           => $sppr->perubahan_posisi ?? '-',
            'lokasi_perumahan'           => $namaPerum,
            'nominal_booking'            => $fmt($sppr->jumlah_booking_fee),
            'keterangan_booking'         => $sppr->keterangan_booking ?? '-',
            'nominal_dp'                 => $fmt($sppr->nominal_dp),
            'keterangan_dp'              => $sppr->keterangan_dp ?? '-',
            'nominal_biaya_posisi_unit'  => $fmt($sppr->nominal_biaya_posisi_unit),
            'keterangan_posisi_unit'     => $sppr->keterangan_posisi_unit ?? '-',
            'nominal_biaya_kpr'          => $fmt($sppr->nominal_biaya_kpr),
            'keterangan_kpr'             => $sppr->keterangan_kpr ?? '-',
            'nominal_blokir_angsuran'    => $fmt($sppr->nominal_blokir_angsuran),
            'keterangan_blokir_angsuran' => $sppr->keterangan_blokir_angsuran ?? '-',
            'nominal_biaya_materai'      => $fmt($sppr->nominal_biaya_materai),
            'keterangan_materai'         => $sppr->keterangan_materai ?? '-',
            'nominal_biaya_buka_tabungan'=> $fmt($sppr->nominal_biaya_buka_tabungan),
            'keterangan_tabungan'        => $sppr->keterangan_tabungan ?? '-',
            'biaya_peningkatan_shm'      => $fmt($sppr->peningkatan_mutu),
            'keterangan_shm'             => $sppr->keterangan_shm ?? '-',
            'total_biaya'                => $fmt(
                (int) $sppr->jumlah_booking_fee
                + (int) $sppr->nominal_dp
                + (int) $sppr->nominal_biaya_posisi_unit
                + (int) $sppr->nominal_biaya_kpr
                + (int) $sppr->nominal_blokir_angsuran
                + (int) $sppr->nominal_biaya_materai
                + (int) $sppr->nominal_biaya_buka_tabungan
                + (int) $sppr->peningkatan_mutu
                + 4000000
            ),
        ]);

        $filename = 'SPPR_' . str_replace(' ', '_', $sppr->nama) . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'sppr_');
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }


    private function hitungTotal(Request $request)
    {
        return (int) $request->jumlah_booking_fee
            + (int) ($request->nominal_dp ?? 0)
            + (int) ($request->nominal_biaya_posisi_unit ?? 0)
            + (int) ($request->nominal_biaya_kpr ?? 0)
            + (int) ($request->nominal_blokir_angsuran ?? 0)
            + (int) ($request->nominal_biaya_materai ?? 0)
            + (int) ($request->nominal_biaya_buka_tabungan ?? 0)
            + (int) $request->peningkatan_mutu
            + 4000000;
    }
}
