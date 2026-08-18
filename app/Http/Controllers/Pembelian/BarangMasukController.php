<?php

namespace App\Http\Controllers\Pembelian;

use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use App\Models\Barang;
use App\Models\InputPO;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use App\Models\InputPODetail;
use App\Models\BarangMasukDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Pengaturan\HakAksesController;

class BarangMasukController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = BarangMasuk::orderBy('id', 'desc');

            $dataTable = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    $inputPO = InputPO::find($row->id_po);
                    $tanggal = Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                    $badge = '<div><span class="badge bg-primary">' . e($inputPO->no_po) . '</span></div>';

                    return $tanggal . $badge;
                })
                ->addColumn('supplier', function ($row) {
                    $inputPO = InputPO::find($row->id_po);
                    $namaSupplier = Supplier::find($inputPO->id_supplier);
                    return $namaSupplier ? $namaSupplier->nama : 'Supplier Tidak Diketahui';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $showUrl = route('barang-masuk.show', $row->id);
                    $deleteUrl = route('barang-masuk.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<a href="' . e($showUrl) . '" class="btn btn-primary btn-sm mx-1">Edit</a>';
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
                ->rawColumns(['action', 'tanggal'])
                ->make(true);

            return $dataTable;
        }

        return view('admin.pembelian.barang_masuk.index', compact('permissions'));
    }

    public function create(Request $request)
    {
        $usedPoIds = BarangMasuk::pluck('id_po')->toArray();
        $poList = InputPO::whereNotIn('id', $usedPoIds)->where('status', 3)->get();

        if ($request->ajax()) {
            $data = InputPODetail::where('id_po', $request->id_po)->orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id_barang_nama', function ($row) {
                    $barang = Barang::find($row->id_barang);
                    return $barang ? $barang->nama : 'Barang Tidak Diketahui';
                })
                ->addColumn('id_barang', function ($row) {
                    return $row->id_barang;
                })
                ->make(true);
        }

        return view('admin.pembelian.barang_masuk.create', compact('poList'));
    }

    public function getPO($id)
    {
        $po = InputPO::find($id);

        if (!$po) {
            return response()->json(['message' => 'Data PO tidak ditemukan'], 404);
        }

        $supplier = Supplier::find($po->id_supplier);

        return response()->json([
            'tanggal' => $po->tanggal,
            'supplier' => $supplier ? $supplier->nama : '-',
            'keterangan' => $po->keterangan
        ]);
    }

    public function validateJumlah(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|integer',
            'jumlah' => 'required|integer',
            'id_po' => 'required|string',
        ]);

        $id_po = InputPO::where('id', $request->id_po)->first();
        if (!$id_po) {
            return response()->json(['valid' => false, 'message' => 'PO tidak ditemukan.'], 404);
        }

        $soDetail = InputPODetail::where('id_po', $id_po->id)
            ->where('id_barang', $request->id_barang)
            ->first();

        if (!$soDetail) {
            return response()->json(['valid' => false, 'message' => 'Barang tidak ditemukan dalam order.'], 404);
        }

        if ($request->jumlah > $soDetail->jumlah) {
            return response()->json([
                'valid' => false,
                'message' => 'Melebihi jumlah Order (' . $soDetail->jumlah . ')'
            ]);
        }

        return response()->json(['valid' => true]);
    }

    public function show(Request $request, string $id)
    {
        $data = BarangMasuk::findOrFail($id);
        $data_po = InputPO::find($data->id_po);
        $supplier = Supplier::find($data_po->id_supplier)->nama;

        if ($request->ajax()) {
            $data = BarangMasukDetail::where('id_masuk', $data->id)->orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id_barang_nama', function ($row) {
                    $barang = Barang::find($row->id_barang);
                    return $barang ? $barang->nama : 'Barang Tidak Diketahui';
                })
                ->addColumn('id_barang', function ($row) {
                    return $row->id_barang;
                })
                ->addColumn('jumlah', function ($row) {
                    return $row->jumlah;
                })
                ->addColumn('stok', function ($row) {
                    return $row->stok;
                })
                ->addColumn('keterangan', function ($row) {
                    return $row->keterangan;
                })
                ->make(true);
        }

        return view('admin.pembelian.barang_masuk.show', compact('data', 'data_po', 'supplier'));
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $rules = [
            'id_po' => 'required',
            'nama_penerima' => 'required',
            'tanggal' => 'required',
            'id_barang' => 'required|array',
            'id_barang.*' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable',
        ];

        $messages = [
            'id_po.required' => 'PO harus dipilih.',
            'nama_penerima.required' => 'Nama penerima harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'id_barang.required' => 'Barang harus dipilih.',
            'id_barang.*.required' => 'Barang harus dipilih untuk setiap item.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'jumlah.*.required' => 'Jumlah harus diisi untuk setiap barang.',
            'harga_beli.required' => 'Harga beli harus diisi.',
            'harga_beli.*.required' => 'Harga beli harus diisi untuk setiap barang.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $data = BarangMasuk::create([
                'id_po' => $request->id_po,
                'tanggal' => $request->tanggal,
                'nama_penerima' => $request->nama_penerima,
            ]);

            foreach ($request->id_barang as $index => $id_barang) {
                $jumlah = (int) str_replace('.', '', $request->jumlah[$index]);
                $hargaBeli = (int) str_replace('.', '', $request->harga_beli[$index]);

                BarangMasukDetail::create([
                    'id_masuk' => $data->id,
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'keterangan' => $request->keterangan[$index] ?? '',
                    'harga_beli' => $hargaBeli,
                    'sub_total' => $hargaBeli * $jumlah,
                ]);

                $barang = Barang::find($id_barang);

                $barang->stok += $jumlah;
                $barang->harga_beli = $hargaBeli;
                $barang->save();
            }

            $this->logCreate('Barang Masuk', $data->id);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Barang Masuk berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error saving Barang Masuk: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan Barang Masuk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = BarangMasuk::findOrFail($id);

        $rules = [
            'nama_penerima' => 'required',
            'tanggal' => 'required',
            'id_barang' => 'required|array',
            'id_barang.*' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable',
        ];

        $messages = [
            'nama_penerima.required' => 'Nama penerima harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'id_barang.required' => 'Barang harus dipilih.',
            'id_barang.*.required' => 'Barang harus dipilih untuk setiap item.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'jumlah.*.required' => 'Jumlah harus diisi untuk setiap barang.',
            'harga_beli.required' => 'Harga beli harus diisi.',
            'harga_beli.*.required' => 'Harga beli harus diisi untuk setiap barang.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $data->update([
                'tanggal' => $request->tanggal,
                'nama_penerima' => $request->nama_penerima,
            ]);
            $this->logEdit('Barang Masuk', $data->id);

            $cekSO = BarangMasukDetail::where('id_masuk', $data->id)->exists();
            if ($cekSO) {
                $SOD = BarangMasukDetail::where('id_masuk', $data->id)->get();
                foreach ($SOD as $item) {
                    $barang = Barang::find($item->id_barang);
                    if ($barang) {
                        $barang->stok -= $item->jumlah;
                        $barang->save();
                    }
                }
            }

            BarangMasukDetail::where('id_masuk', $data->id)->delete();

            foreach ($request->id_barang as $index => $id_barang) {
                $jumlah = (int) str_replace('.', '', $request->jumlah)[$index];
                $hargaBeli = (int) str_replace('.', '', $request->harga_beli)[$index];

                BarangMasukDetail::create([
                    'id_masuk' => $data->id,
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'keterangan' => $request->keterangan[$index] ?? '',
                    'harga_beli' => $hargaBeli,
                    'sub_total' => $hargaBeli * $jumlah,
                ]);

                $barang = Barang::find($id_barang);

                $barang->stok += $jumlah;
                $barang->harga_beli = $hargaBeli;
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Barang Masuk berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan Barang Masuk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $data = BarangMasuk::findOrFail($id);
        $BM = BarangMasukDetail::where('id_masuk', $data->id)->get();
        foreach ($BM as $item) {
            $barang = Barang::find($item->id_barang);
            if ($barang) {
                $barang->stok -= $item->stok;
                $barang->save();
            }
        }

        BarangMasukDetail::where('id_masuk', $data->id)->delete();

        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
