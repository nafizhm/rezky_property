<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\ListPenjualan;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ListPenjualanController extends Controller
{
    use LogAktivitasTrait;

    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = ListPenjualan::orderBy('urutan', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('warna', function ($row) {
                    return '<div style="display: flex; align-items: center;">
                            <div style="width: 15px; height: 15px; background-color: '.$row->warna.'; border: 1px solid #000; margin-right: 5px;"></div>
                            <span>'.$row->warna.'</span>
                        </div>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl = route('list-penjualan.edit', $row->id);
                    $deleteUrl = route('list-penjualan.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="'.e($row->id).'"
                                data-url="'.e($editUrl).'">Edit</button>';
                    }

                    // if ($permissions['hapus']) {
                    //     $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                    // ' . csrf_field() . method_field('DELETE') . '
                    // <button type="submit" class="delete-button btn btn-danger btn-sm mx-1">
                    //     Hapus
                    // </button>
                    // </form>';
                    // }
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action', 'warna'])
                ->make(true);
        }

        return view('admin.pengaturan.list_penjualan.index', compact('permissions'));
    }

    public function edit($id)
    {
        $list = ListPenjualan::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_progres' => 'required',
            'keterangan' => 'nullable',
            'warna' => 'required',
            'urutan' => 'required',
            'short_name' => 'required',
            'stt_tampil' => 'required',
        ], [
            'status_progres.required' => 'Progres wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'urutan.required' => 'Urutan wajib diisi.',
            'short_name.required' => 'Short Name wajib diisi.',
            'stt_tampil.required' => 'Status Tampil wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $db = [
                'status_progres' => $request->status_progres,
                'keterangan' => $request->keterangan ?? '',
                'warna' => $request->warna,
                'urutan' => $request->urutan,
                'short_name' => $request->short_name,
                'stt_tampil' => $request->stt_tampil,
            ];

            $l = ListPenjualan::create($db);
            $this->logCreate('List Penjualan', $l->id);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = ListPenjualan::findOrFail($id);

        $request->validate([
            'status_progres' => 'required',
            'keterangan' => 'nullable',
            'warna' => 'required',
            'urutan' => 'required',
            'short_name' => 'required',
            'stt_tampil' => 'required',
        ], [
            'status_progres.required' => 'Progres wajib diisi.',
            'warna.required' => 'Warna wajib diisi.',
            'urutan.required' => 'Urutan wajib diisi.',
            'short_name.required' => 'Short Name wajib diisi.',
            'stt_tampil.required' => 'Status Tampil wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $db = [
                'status_progres' => $request->status_progres,
                'keterangan' => $request->keterangan ?? '',
                'warna' => $request->warna,
                'urutan' => $request->urutan,
                'short_name' => $request->short_name,
                'stt_tampil' => $request->stt_tampil,
            ];

            $data->update($db);
            $this->logEdit('List Penjualan', $data->id);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $data = ListPenjualan::findOrFail($id);

        $this->logDelete('List Penjualan', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
