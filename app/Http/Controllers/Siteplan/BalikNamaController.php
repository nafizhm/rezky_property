<?php
namespace App\Http\Controllers\Siteplan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\BalikNama;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Customer;

class BalikNamaController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $opd = BalikNama::orderBy('id', 'desc');

            return DataTables::of($opd)
                ->addIndexColumn()
                ->addColumn('lokasi_rumah', function ($row) {
                    $lokasi  = LokasiKavling::find($row->id_lokasi);
                    $kavling = KavlingPeta::find($row->id_kavling);

                    $namaLokasi  = $lokasi ? $lokasi->nama_kavling : '-';
                    $kodeKavling = $kavling ? $kavling->kode_kavling : '-';

                    return '<div>' . e($namaLokasi) . '</div>' .
                    '<span class="badge badge-info">' . e($kodeKavling) . '</span>';
                })

                ->addColumn('id_customer', function ($row) {
                        $customer = Customer::find($row->id_customer);
                        return $customer ? $customer->nama_lengkap : '-';
                    })

                ->editColumn('stt_balik', function ($row) {
                    if ($row->stt_balik === 'sudah') {
                        return '<span class="badge badge-success">Sudah</span>';
                    }

                    if ($row->stt_balik === 'belum') {
                        return '<span class="badge badge-danger">Belum</span>';
                    }

                    return '-';
                })

                ->addColumn('action', function ($row) use ($permissions): string {
                    $editUrl   = route('balik-nama.edit', $row->id);
                    $deleteUrl = route('balik-nama.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['lokasi_rumah', 'action', 'stt_balik'])
                ->make(true);
        }
        $lokasi = LokasiKavling::all();
        $customers = Customer::select('id', 'nama_lengkap')
            ->orderBy('nama_lengkap')
            ->get();


        return view('admin.siteplan.balik_nama.index', compact('permissions', 'lokasi', 'customers'));
    }

    public function edit($id)
    {
        $list = BalikNama::findOrFail($id);

        return response()->json([
            'status'             => 'success',
            'data'               => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_lokasi'      => 'required',
            'id_kavling'     => 'required',
            'id_customer' => 'required',
            'nama_pengganti'  => 'required',
            'stt_balik'  => 'required',
        ], [
            'id_customer.required'      => 'Customer wajib diisi.',
            'id_lokasi.required'      => 'Lokasi perumahan wajib diisi.',
            'id_kavling.required'     => 'Blok/Kavling wajib diisi.',
            'nama_pengganti.required' => 'Nama Pengganti wajib diisi.',
            'stt_balik.required'      => 'Stt Balik wajib diisi.',
        ]);

        DB::beginTransaction();
        try {

            $data = [
                'id_customer' => $request->id_customer,
                'id_lokasi'      => $request->id_lokasi,
                'id_kavling'     => $request->id_kavling,
                'nama_pengganti' => $request->nama_pengganti,
                'stt_balik'      => $request->stt_balik,
            ];

            BalikNama::create($data);


            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = BalikNama::findOrFail($id);

        $request->validate([
            'id_lokasi'      => 'required|integer',
            'id_kavling'     => 'required|integer',
            'id_customer'    => 'required|integer',
            'nama_pengganti' => 'required|string|max:255',
            'stt_balik'      => 'required',
        ], [
            'id_lokasi.required'      => 'Lokasi wajib dipilih',
            'id_kavling.required'     => 'Kavling wajib dipilih',
            'id_customer.required'    => 'Customer wajib dipilih',
            'nama_pengganti.required' => 'Nama pengganti wajib diisi',
            'stt_balik.required'      => 'Status balik nama wajib dipilih',
        ]);

        DB::beginTransaction();

        try {

            $data->update([
                'id_lokasi'      => $request->id_lokasi,
                'id_kavling'     => $request->id_kavling,
                'id_customer'    => $request->id_customer,
                'nama_pengganti' => $request->nama_pengganti,
                'stt_balik'      => $request->stt_balik,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Data berhasil diperbarui'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data'
            ], 500);
        }
    }


    public function destroy($id)
    {
        $prospek = BalikNama::findOrFail($id);
        $this->logEdit('BPHTB & SSP', $prospek->id);
        $prospek->delete();

        return response()->json(['status' => 'success']);
    }
}
