<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Notaris;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotarisController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Notaris::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('notaris.edit', $row->id);
                    $deleteUrl = route('notaris.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . e($row->id) . '"
                                data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-button btn btn-danger btn-sm">
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

        return view('admin.master.notaris.index', compact('permissions'));
    }

    public function edit($id)
    {
        $list = Notaris::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_notaris'       => 'required|unique:notaris,nama_notaris',
            'telp_notaris'       => 'required|unique:notaris,telp_notaris',
            'alamat_notaris'     => 'required',
            'keterangan_notaris' => 'nullable',

        ], [
            'nama_notaris.required'   => 'Nama Notaris wajib diisi.',
            'nama_notaris.unique'     => 'Nama Notaris sudah digunakan.',
            'telp_notaris.required'   => 'No Telepon wajib diisi.',
            'telp_notaris.unique'     => 'No Telepon sudah digunakan.',
            'alamat_notaris.required' => 'Alamat wajib diisi.',
        ]);

        $db = [
            'nama_notaris'       => $request->nama_notaris,
            'telp_notaris'       => $request->telp_notaris,
            'alamat_notaris'     => $request->alamat_notaris,
            'keterangan_notaris' => $request->keterangan_notaris ?? '',
        ];

        $not = Notaris::create($db);
        $this->logCreate('Notaris', $not->id);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = Notaris::findOrFail($id);

        $request->validate([
            'nama_notaris'       => 'required|unique:notaris,nama_notaris,' . $id . ',id',
            'telp_notaris'       => 'required|unique:notaris,telp_notaris,' . $id . ',id',
            'alamat_notaris'     => 'required',
            'keterangan_notaris' => 'nullable',

        ], [
            'nama_notaris.required'   => 'Nama Notaris wajib diisi.',
            'nama_notaris.unique'     => 'Nama Notaris sudah digunakan.',
            'telp_notaris.required'   => 'No Telepon wajib diisi.',
            'telp_notaris.unique'     => 'No Telepon sudah digunakan.',
            'alamat_notaris.required' => 'Alamat wajib diisi.',
        ]);

        $db = [
            'nama_notaris'       => $request->nama_notaris,
            'telp_notaris'       => $request->telp_notaris,
            'alamat_notaris'     => $request->alamat_notaris,
            'keterangan_notaris' => $request->keterangan_notaris ?? '',
        ];

        $data->update($db);
        $this->logEdit('Notaris', $data->id);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = Notaris::findOrFail($id);

        $this->logDelete('Notaris', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
