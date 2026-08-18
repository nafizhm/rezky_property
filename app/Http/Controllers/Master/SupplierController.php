<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Supplier;
use App\Traits\LogAktivitasTrait;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class SupplierController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Supplier::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('status', function ($row) {
                    switch ($row->status) {
                        case 1:
                            return '<span class="badge bg-primary">Aktif</span>';
                        case 2:
                            return '<span class="badge bg-danger">Tidak Aktif</span>';
                        default:
                            return '<span class="badge bg-secondary">Tidak Diketahui</span>';
                    }
                })

                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl = route('supplier.edit', $row->id);
                    $deleteUrl = route('supplier.destroy', $row->id);

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

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.master.supplier.index', compact('permissions'));
    }

    public function edit($id)
    {
        $list = Supplier::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:supplier,nama',
            'no_telp' => 'required|unique:supplier,no_telp',
            'alamat' => 'required',
            'keterangan' => 'nullable',
            'status' => 'required',

        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah digunakan.',
            'no_telp.required' => 'No Telepon wajib diisi.',
            'no_telp.unique' => 'No Telepon sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ]);

        $latest = Supplier::where('kode', 'like', 'S-%')->orderBy('kode', 'desc')->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->kode, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kode = 'S-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $db = [
            'kode' => $kode,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan ?? '',
            'status' => $request->status,
        ];

        $sup = Supplier::create($db);
        $this->logCreate('Supplier', $sup->id);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = Supplier::findOrFail($id);

       $request->validate([
            'nama' => 'required|unique:supplier,nama,' . $id . ',id',
            'no_telp' => 'required|unique:supplier,no_telp,' . $id . ',id',
            'alamat' => 'required',
            'keterangan' => 'nullable',
            'status' => 'required',

        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah digunakan.',
            'no_telp.required' => 'No Telepon wajib diisi.',
            'no_telp.unique' => 'No Telepon sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ]);

        $db = [
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'keterangan' => $request->keterangan ?? '',
            'status' => $request->status,
        ];

        $data->update($db);
        $this->logEdit('Supplier', $data->id);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = Supplier::findOrFail($id);

        $this->logDelete('Supplier', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
