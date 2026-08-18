<?php
namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\KategoriTransaksi;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KategoriTransaksiController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KategoriTransaksi::orderBy('id', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $showUrl = route('kategori-transaksi.show', $row->id);
                    $deleteUrl = route('kategori-transaksi.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($row->stt_fix == 1) {
                        $btn .= '<button class="btn btn-secondary btn-sm" disabled>Edit</button>';
                        $btn .= '<button class="btn btn-secondary btn-sm ml-2" disabled>Hapus</button>';
                    } else {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($showUrl) . '">Edit</button>';
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">'
                            . csrf_field() . method_field('DELETE')
                            . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button>'
                            . '</form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.keuangan.kategori_transaksi.index');
    }

    public function show($id)
    {
        $data = KategoriTransaksi::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'kode' => 'required|unique:kategori_transaksi,kode',
            'kategori' => 'required|unique:kategori_transaksi,kategori',
            'jenis_kategori' => 'required',
        ];

        $messages = [
            'kode.required' => 'Kode wajib diisi.',
            'kode.unique' => 'Kode sudah digunakan.',
            'kategori.required' => 'Kategori wajib diisi.',
            'kategori.unique' => 'Kategori sudah digunakan.',
            'jenis_kategori.required' => 'Jenis kategori wajib dipilih.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $db = [
                'kode' => $request->kode,
                'kategori' => $request->kategori,
                'jenis_kategori' => $request->jenis_kategori,
                'stt_fix' => 0,
            ];

            $kt = KategoriTransaksi::create($db);
            $this->logCreate('Kategori Transaksi', $kt->id);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = KategoriTransaksi::findOrFail($id);

        $rules = [
            'kode' => 'required|unique:kategori_transaksi,kode,' . $data->id . ',id',
            'kategori' => 'required|unique:kategori_transaksi,kategori,' . $data->id . ',id',
            'jenis_kategori' => 'required',
        ];

        $messages = [
            'kode.required' => 'Kode wajib diisi.',
            'kode.unique' => 'Kode sudah digunakan.',
            'kategori.required' => 'Kategori wajib diisi.',
            'kategori.unique' => 'Kategori sudah digunakan.',
            'jenis_kategori.required' => 'Jenis kategori wajib dipilih.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $db = [
                'kode' => $request->kode,
                'kategori' => $request->kategori,
                'jenis_kategori' => $request->jenis_kategori,
                'stt_fix' => 0,

            ];

            $data->update($db);
            $this->logEdit('Kategori Transaksi', $data->id);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = KategoriTransaksi::findOrFail($id);
            $this->logDelete('Kategori Transaksi', $data->id);
            $data->delete();

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
