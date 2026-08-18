<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\BankKPR;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BankKPRController extends Controller
{
    use LogAktivitasTrait;

    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = BankKPR::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl = route('bank-kpr.edit', $row->id);
                    $deleteUrl = route('bank-kpr.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button" data-id="'.e($row->id).'" data-url="'.e($editUrl).'">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="'.e($deleteUrl).'" method="POST" style="display:inline;">'.csrf_field().method_field('DELETE').'<button type="submit" class="delete-button btn btn-danger btn-sm mx-1">Hapus</button></form>';
                    }
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.master.bank_kpr.index', compact('permissions'));
    }

    public function edit($id)
    {
        $list = BankKPR::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:bank_kpr,nama',
        ], [
            'nama.required' => 'Nama bank wajib diisi.',
            'nama.unique' => 'Nama bank sudah digunakan.',
        ]);

        $db = [
            'nama' => $request->nama,
        ];

        $bk = BankKPR::create($db);
        $this->logCreate('Bank KPR', $bk->id);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = BankKPR::findOrFail($id);

        $request->validate([
            'nama' => 'required|unique:bank_kpr,nama,'.$data->id.',id',
        ], [
            'nama.required' => 'Nama bank wajib diisi.',
            'nama.unique' => 'Nama bank sudah digunakan.',
        ]);

        $db = [
            'nama' => $request->nama,
        ];

        $data->update($db);
        $this->logEdit('Bank KPR', $data->id);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = BankKPR::findOrFail($id);

        $this->logDelete('Bank KPR', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
