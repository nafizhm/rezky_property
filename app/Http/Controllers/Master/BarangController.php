<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Supplier;
use App\Traits\LogAktivitasTrait;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Barang::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('stok_awal', fn($row) => number_format($row->stok_awal))
                ->editColumn('harga_beli', fn($row) => '<div class="d-flex justify-content-between"><span>Rp.</span><span>' . number_format($row->harga_beli, 0, ',', '.') . '</span></div>')
                ->editColumn('harga_jual', fn($row) => '<div class="d-flex justify-content-between"><span>Rp.</span><span>' . number_format($row->harga_jual, 0, ',', '.') . '</span></div>')
                ->addColumn('id_satuan', function ($row) {
                    $satuan = Satuan::find($row->id_satuan);
                    return $satuan ? $satuan->nama_satuan : '';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl = route('barang.edit', $row->id);
                    $deleteUrl = route('barang.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm mx-1">Hapus</button></form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['harga_beli', 'harga_jual', 'action'])
                ->make(true);
        }

        $suppliers = Supplier::all();
        $satuans = Satuan::all();

        return view('admin.master.barang.index', compact('permissions', 'suppliers', 'satuans'));
    }

    public function edit($id)
    {
        $list = Barang::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:barang,nama',
            'sku' => 'required|unique:barang,sku',
            'id_satuan' => 'required',
            'id_supplier' => 'required',
            'stok_awal' => 'required',
            'stok_minimal' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stok' => 'required',
            'deskripsi' => 'nullable',
        ], [
            'nama.required' => 'Nama barang wajib diisi.',
            'nama.unique' => 'Nama barang sudah digunakan.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan.',
            'id_satuan.required' => 'Satuan wajib dipilih.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'stok_awal.required' => 'Stok awal wajib diisi.',
            'stok_minimal.required' => 'Stok minimal wajib diisi.',
            'harga_beli.required' => 'Harga Beli wajib diisi.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
        ]);

        $db = [
            'nama' => $request->nama,
            'sku' => $request->sku,
            'id_satuan' => $request->id_satuan,
            'id_supplier' => $request->id_supplier,
            'stok_awal' => str_replace('.', '', $request->stok_awal),
            'stok_minimal' => str_replace('.', '', $request->stok_minimal),
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
            'stok' => str_replace('.', '', $request->stok),
            'deskripsi' => $request->deskripsi ?? '',
        ];

        $brg = Barang::create($db);
        $this->logCreate('Barang', $brg->id);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = Barang::findOrFail($id);

        $request->validate([
            'nama' => 'required|unique:barang,nama,' . $data->id . ',id',
            'sku' => 'required|unique:barang,sku,' . $data->id . ',id',
            'id_satuan' => 'required',
            'id_supplier' => 'required',
            'stok_awal' => 'required',
            'stok_minimal' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stok' => 'required',
            'deskripsi' => 'nullable',
        ], [
            'nama.required' => 'Nama barang wajib diisi.',
            'nama.unique' => 'Nama barang sudah digunakan.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan.',
            'id_satuan.required' => 'Satuan wajib dipilih.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'stok_awal.required' => 'Stok awal wajib diisi.',
            'stok_minimal.required' => 'Stok minimal wajib diisi.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
        ]);

        $db = [
            'nama' => $request->nama,
            'sku' => $request->sku,
            'id_satuan' => $request->id_satuan,
            'id_supplier' => $request->id_supplier,
            'stok_awal' => str_replace('.', '', $request->stok_awal),
            'stok_minimal' => str_replace('.', '', $request->stok_minimal),
            'harga_beli' => str_replace('.', '', $request->harga_beli),
            'harga_jual' => str_replace('.', '', $request->harga_jual),
            'stok' => str_replace('.', '', $request->stok),
            'deskripsi' => $request->deskripsi ?? '',
        ];

        $data->update($db);
        $this->logEdit('Barang', $data->id);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = Barang::findOrFail($id);

        $this->logDelete('Barang', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
