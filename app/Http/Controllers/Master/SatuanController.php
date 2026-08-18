<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Satuan;
use App\Traits\LogAktivitasTrait;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class SatuanController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Satuan::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl = route('satuan.edit', $row->id);
                    $deleteUrl = route('satuan.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button" 
                                data-id="' . e($row->id) . '" 
                                data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-button btn btn-danger btn-sm mx-1">
                        Hapus
                    </button>
                    </form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.master.satuan.index', compact('permissions'));
    }

    public function edit($id)
    {
        $list = Satuan::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|unique:satuan,nama_satuan',

        ], [
            'nama_satuan.required' => 'Nama Satuan wajib diisi.',
            'nama_satuan.unique' => 'Nama Satuan sudah digunakan.',
        ]);

        $db = [
            'nama_satuan' => $request->nama_satuan,
        ];

        $sat = Satuan::create($db);
        $this->logCreate('Satuan', $sat->id);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = Satuan::findOrFail($id);

         $request->validate([
            'nama_satuan' => 'required|unique:satuan,nama_satuan,' . $data->id . ',id',

        ], [
            'nama_satuan.required' => 'Nama Satuan wajib diisi.',
            'nama_satuan.unique' => 'Nama Satuan sudah digunakan.',
        ]);

        $db = [
            'nama_satuan' => $request->nama_satuan,
        ];

        $data->update($db);
        $this->logEdit('Satuan', $data->id);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = Satuan::findOrFail($id);

        $this->logDelete('Satuan', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
