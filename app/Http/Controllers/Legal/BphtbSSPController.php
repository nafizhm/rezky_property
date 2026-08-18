<?php
namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\BphtbSsp;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BphtbSSPController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $opd = BphtbSsp::orderBy('id', 'desc');

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

                ->editColumn('status_bphtb', function ($row) {
                    if ($row->status_bphtb === 'ada') {
                        return '<span class="badge badge-success">Ada</span>';
                    }

                    if ($row->status_bphtb === 'tidak ada') {
                        return '<span class="badge badge-danger">Tidak Ada</span>';
                    }

                    return '-';
                })

                ->editColumn('status_ssp', function ($row) {
                    if ($row->status_ssp === 'ada') {
                        return '<span class="badge badge-success">Ada</span>';
                    }

                    if ($row->status_ssp === 'tidak ada') {
                        return '<span class="badge badge-danger">Tidak Ada</span>';
                    }

                    return '-';
                })

                ->addColumn('action', function ($row) use ($permissions): string {
                    $editUrl   = route('bphtb-ssp.edit', $row->id);
                    $deleteUrl = route('bphtb-ssp.destroy', $row->id);

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
                ->rawColumns(['lokasi_rumah', 'action', 'status_bphtb', 'status_ssp'])
                ->make(true);
        }
        $lokasi = LokasiKavling::all();



        return view('admin.legal.bphtb_ssp.index', compact('permissions', 'lokasi'));
    }

    public function edit($id)
    {
        $list = BphtbSsp::findOrFail($id);

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
            'status_bphtb'        => 'required',
            'status_ssp'        => 'required',
        ], [
            'id_lokasi.required'      => 'Lokasi perumahan wajib diisi.',
            'id_kavling.required'     => 'Blok/Kavling wajib diisi.',
            'status_bphtb.required'      => 'Status bphtb wajib diisi.',
            'status_ssp.required'      => 'Status ssp wajib diisi.',
        ]);

        DB::beginTransaction();
        try {

            $data = [
                'id_lokasi'      => $request->id_lokasi,
                'id_kavling'     => $request->id_kavling,
                'status_bphtb'      => $request->status_bphtb,
                'status_ssp'      => $request->status_ssp,
            ];

            BphtbSsp::create($data);


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
        $data = BphtbSsp::findOrFail($id);

       $request->validate([
            'id_lokasi'      => 'required',
            'id_kavling'     => 'required',
            'status_bphtb'        => 'required',
            'status_ssp'        => 'required',
        ], [
            'id_lokasi.required'      => 'Lokasi perumahan wajib diisi.',
            'id_kavling.required'     => 'Blok/Kavling wajib diisi.',
            'status_bphtb.required'      => 'Status bphtb wajib diisi.',
            'status_ssp.required'      => 'Status ssp wajib diisi.',
        ]);

        DB::beginTransaction();

        try {

            $data->update([
               'id_lokasi'      => $request->id_lokasi,
                'id_kavling'     => $request->id_kavling,
                'status_bphtb'      => $request->status_bphtb,
                'status_ssp'      => $request->status_ssp,
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
        $prospek = BphtbSsp::findOrFail($id);
        $prospek->delete();

        return response()->json(['status' => 'success']);
    }
}
