<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Retensi;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RetensiController extends Controller
{
    use LogAktivitasTrait;

    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Retensi::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl = route('retensi.edit', $row->id);
                    $deleteUrl = route('retensi.destroy', $row->id);

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

        return view('admin.master.retensi.index', compact('permissions'));
    }

    public function edit($id)
    {
        $retensi = Retensi::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $retensi,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_retensi' => 'required|unique:retensi,nama_retensi',
            'keterangan' => 'nullable|string',
        ], [
            'nama_retensi.required' => 'Nama retensi wajib diisi.',
            'nama_retensi.unique' => 'Nama retensi sudah digunakan.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
        ]);

        $retensi = Retensi::create([
            'nama_retensi' => $request->nama_retensi,
            'keterangan' => $request->keterangan,
        ]);

        $this->logCreate('Retensi', $retensi->id);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $retensi = Retensi::findOrFail($id);

        $request->validate([
            'nama_retensi' => 'required|unique:retensi,nama_retensi,' . $retensi->id . ',id',
            'keterangan' => 'nullable|string',
        ], [
            'nama_retensi.required' => 'Nama retensi wajib diisi.',
            'nama_retensi.unique' => 'Nama retensi sudah digunakan.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
        ]);

        $retensi->update([
            'nama_retensi' => $request->nama_retensi,
            'keterangan' => $request->keterangan,
        ]);

        $this->logEdit('Retensi', $retensi->id);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $retensi = Retensi::findOrFail($id);

        $this->logDelete('Retensi', $retensi->id);
        $retensi->delete();

        return response()->json(['status' => 'success']);
    }
}
