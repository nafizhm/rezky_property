<?php

namespace App\Http\Controllers\Pembelian;

use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Barang;
use App\Models\InputPO;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InputPODetail;
use App\Models\InputPOPembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\Pengeluaran;

class InputPOController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = InputPO::orderBy('id', 'desc');

            $dataTable = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    $tanggal = Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                    $badge = '<div><span class="badge bg-primary">' . e($row->no_po) . '</span></div>';

                    return $tanggal . $badge;
                })
                ->addColumn('supplier', function ($row) {
                    $namaSupplier = Supplier::find($row->id_supplier);
                    return $namaSupplier ? $namaSupplier->nama : 'Supplier Tidak Diketahui';
                })
                ->addColumn('total_harga', function ($row) {
                    return '<div class="d-flex justify-content-between">
                <span>Rp.</span>
                <span>' . number_format($row->total_harga, 0, ',', '.') . '</span>
            </div>';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-secondary">Proses Pemesanan</span>';
                    } elseif ($row->status == 2) {
                        return '<span class="badge bg-success">Proses Pengiriman</span>';
                    } elseif ($row->status == 3) {
                        return '<span class="badge bg-primary">Barang Diterima</span>';
                    } else {
                        return '<span class="badge bg-dark">-</span>';
                    }
                })
                ->addColumn('terbayar', function ($row) {
                    $totalTerbayar = InputPOPembayaran::where('id_po', $row->id)->sum('terbayar');
                    return '<div class="d-flex justify-content-between">
                <span>Rp.</span>
                <span>' . number_format($totalTerbayar, 0, ',', '.') . '</span>
            </div>';
                })
                ->addColumn('sisa_bayar', function ($row) {
                    $totalTerbayar = InputPOPembayaran::where('id_po', $row->id)->sum('terbayar');
                    $sisaBayar = $row->total_harga - $totalTerbayar;
                    return '<div class="d-flex justify-content-between">
                <span>Rp.</span>
                <span>' . number_format($sisaBayar, 0, ',', '.') . '</span>
            </div>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $showUrl = route('input-po.show', $row->id);
                    $editUrl = route('input-po.edit', $row->id);
                    $deleteUrl = route('input-po.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<a href="' . e($editUrl) . '" class="btn btn-primary btn-xs mr-1">Edit</a>';
                        $btn .= '<a href="' . e($showUrl) . '" class="btn btn-success btn-xs mr-1">Pembayaran</a>';
                    }
                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="delete-button btn btn-danger btn-xs">
                                Hapus
                            </button>
                        </form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'status', 'total_harga', 'terbayar', 'sisa_bayar', 'tanggal'])
                ->make(true);

            return $dataTable;
        }

        return view('admin.pembelian.input_po.index', compact('permissions'));
    }

    public function create()
    {
        $supplierList = Supplier::all();

        $barangList = Barang::all();

        $bankList = Bank::all();

        return view('admin.pembelian.input_po.create', compact('barangList', 'supplierList', 'bankList'));
    }

    public function edit(Request $request, string $id)
    {
        $dataPO = InputPO::findOrFail($id);

        $supplier = Supplier::where('id', $dataPO->id_supplier)->first()->nama;

        $barangList = Barang::all();

        $dataPOD = InputPODetail::where('id_po', $dataPO->id)->get();

        $dataPOP = InputPOPembayaran::where('id_po', $dataPO->id)->get();

        $total_harga = $dataPOD->sum('subtotal');

        $bankList = Bank::all();

        $supplierList = Supplier::all();

        return view('admin.pembelian.input_po.show', compact('dataPO', 'supplier', 'barangList', 'dataPOD', 'total_harga', 'dataPOP', 'bankList', 'supplierList'));
    }
    public function show(Request $request, string $id)
    {
        $dataPO = InputPO::findOrFail($id);

        $barangList = Barang::all();

        $dataPOD = InputPODetail::where('id_po', $dataPO->id)->get();

        $dataPOP = InputPOPembayaran::where('id_po', $dataPO->id)->get();

        $total_harga = $dataPOD->sum('subtotal');

        $bankList = Bank::all();

        return view('admin.pembelian.input_po.pembayaran', compact('dataPO',  'barangList', 'dataPOD', 'total_harga', 'dataPOP', 'bankList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'id_supplier' => 'required',
            'no_po' => 'required',
            'keterangan' => 'nullable',
            'total_harga' => 'required',
            'id_bank' => 'required',
            'barang' => 'required|array',
            'barang.*' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required',
            'lampiran_po' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $messages = [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'no_po.required' => 'Nomor PO wajib diisi.',
            'total_harga.required' => 'Total harga wajib diisi.',
            'id_bank.required' => 'Bank wajib dipilih.',

            'barang.required' => 'Barang wajib diisi.',
            'barang.array' => 'Data barang tidak valid.',
            'barang.*.required' => 'Barang harus diisi.',

            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.array' => 'Data jumlah tidak valid.',
            'jumlah.*.required' => 'Jumlah harus diisi.',

            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.array' => 'Data harga beli tidak valid.',
            'harga_beli.*.required' => 'Harga harus diisi.',

            'lampiran_po.mimes' => 'File PO harus berupa jpg, jpeg, png, atau pdf.',
            'lampiran_po.max' => 'Ukuran file PO maksimal 2MB.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            if ($request->hasFile('lampiran_po')) {
                $file = $request->file('lampiran_po');
                $ext = $file->getClientOriginalExtension();

                $lampiran_po = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/po/lampiran/'), $lampiran_po);
            }

            $inputPO = InputPO::create([
                'id_bank' => $request->id_bank,
                'tanggal' => $request->tanggal,
                'id_supplier' => $request->id_supplier,
                'no_po' => $request->no_po,
                'total_harga' => (int) str_replace('.', '', $request->total_harga),
                'terbayar' => 0,
                'jum_item' => count($request->barang),
                'keterangan' => $request->keterangan ?? '',
                'status' => 1,
                'lampiran_po' => $lampiran_po ?? '',
            ]);
            $this->logCreate('Input PO', $inputPO->id);

            foreach ($request->barang as $index => $id_barang) {
                $jumlah = (int) str_replace('.', '', $request->jumlah)[$index];
                $harga = (int) str_replace('.', '', $request->harga_beli)[$index];
                $sub_total = $jumlah * $harga;

                InputPODetail::create([
                    'id_po' => $inputPO->id,
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'harga_beli' => $harga,
                    'sub_total' => $sub_total,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'PO berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan PO.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Carbon::setLocale('id');

        $data = InputPO::findOrFail($id);

        $rules = [
            'status' => 'required',
            'tanggal' => 'required|date',
            'id_supplier' => 'required',
            'no_po' => 'required',
            'keterangan' => 'nullable',
            'total_harga' => 'required',
            'id_bank' => 'required',
            'barang' => 'required|array',
            'barang.*' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'required',
            'lampiran_po' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $messages = [
            'status.required' => 'Status wajib dipilih.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'id_supplier.required' => 'Supplier wajib dipilih.',
            'no_po.required' => 'Nomor PO wajib diisi.',
            'total_harga.required' => 'Total harga wajib diisi.',
            'id_bank.required' => 'Bank wajib dipilih.',

            'barang.required' => 'Barang wajib diisi.',
            'barang.array' => 'Data barang tidak valid.',
            'barang.*.required' => 'Barang harus diisi.',

            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.array' => 'Data jumlah tidak valid.',
            'jumlah.*.required' => 'Jumlah harus diisi.',

            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.array' => 'Data harga beli tidak valid.',
            'harga_beli.*.required' => 'Harga harus diisi.',

            'lampiran_po.mimes' => 'File PO harus berupa jpg, jpeg, png, atau pdf.',
            'lampiran_po.max' => 'Ukuran file PO maksimal 2MB.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $lampiran_po = $data->lampiran_po;

            if ($request->hasFile('lampiran_po')) {
                if (!empty($data->lampiran_po) && file_exists(public_path('assets/po/lampiran_po/' . $data->lampiran_po))) {
                    unlink(public_path('assets/po/lampiran/' . $data->lampiran_po));
                }

                $file = $request->file('lampiran_po');
                $ext = $file->getClientOriginalExtension();

                $lampiran_po = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/po/lampiran/'), $lampiran_po);
            }

            $data->update([
                'id_bank' => $request->id_bank,
                'tanggal' => $request->tanggal,
                'id_supplier' => $request->id_supplier,
                'no_po' => $request->no_po,
                'total_harga' => (int) str_replace('.', '', $request->total_harga),
                'jum_item' => count($request->barang),
                'keterangan' => $request->keterangan ?? '',
                'status' => $request->status,
                'lampiran_po' => $lampiran_po,
            ]);
            $this->logEdit('Input PO', $data->id);

            InputPODetail::where('id_po', $data->id)->delete();

            foreach ($request->barang as $index => $id_barang) {
                $jumlah = (int) str_replace('.', '', $request->jumlah)[$index];
                $harga = (int) str_replace('.', '', $request->harga_beli)[$index];
                $sub_total = $jumlah * $harga;

                InputPODetail::create([
                    'id_po' => $data->id,
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'harga_beli' => $harga,
                    'sub_total' => $sub_total,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'PO berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui PO.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function pembayaran(Request $request, $id)
    {
        Carbon::setLocale('id');

        $data = InputPO::findOrFail($id);

        $rules = [
            'total_harga' => 'required',
            'id_bank' => 'required',
            'tanggal_pembayaran' => 'required|array',
            'tanggal_pembayaran.*' => 'required',
            'terbayar' => 'required|array',
            'terbayar.*' => 'required',
            'lampiran' => 'nullable|array',
            'lampiran.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $messages = [
            'total_harga.required' => 'Total harga wajib diisi.',
            'id_bank.required' => 'Bank wajib dipilih.',

            'tanggal_pembayaran.required' => 'Tanggal pembayaran wajib diisi.',
            'tanggal_pembayaran.array' => 'Data tanggal pembayaran tidak valid.',
            'tanggal_pembayaran.*.required' => 'Tanggal pembayaran wajib diisi.',

            'terbayar.required' => 'Terbayar wajib diisi.',
            'terbayar.array' => 'Terbayar tidak valid.',
            'terbayar.*.required' => 'Terbayar wajib diisi.',

            'lampiran.array' => 'Data lampiran tidak valid.',
            'lampiran.*.mimes' => 'Lampiran harus berupa file dengan format jpg, jpeg, png, atau pdf.',
            'lampiran.*.max' => 'Lampiran Max 2MB.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $data->update([
                'id_bank' => $request->id_bank,
                'total_harga' => (int) str_replace('.', '', $request->total_harga),
            ]);

            $totalTerbayar = 0;

            $POPembayaran = InputPOPembayaran::where('id_po', $data->id)->get();
            $lampiranLamaDB = [];
            if ($POPembayaran->count() > 0) {
                foreach ($POPembayaran as $pembayaran) {
                    $lampiranLamaDB[] = !empty($pembayaran->lampiran) ? $pembayaran->lampiran : '';
                }
            }

            InputPOPembayaran::where('id_po', $data->id)->delete();

            $lampiranDipakai = [];

            Pengeluaran::where('id_po', $data->id)->delete();

            foreach ($request->terbayar as $index => $nilai) {
                $terbayar = (int) str_replace('.', '', $nilai);
                $lampiranBaru = '';
                $requestLampiranFile = $request->file('lampiran')[$index] ?? null;
                $lampiranLamaRequest = $request->lampiran_lama[$index] ?? '';
                $lampiranLamaDBVal = $lampiranLamaDB[$index] ?? '';

                if (!$requestLampiranFile && $lampiranLamaRequest && $lampiranLamaRequest === $lampiranLamaDBVal) {
                    $lampiranBaru = $lampiranLamaRequest;
                    $lampiranDipakai[] = $lampiranBaru;
                }

                if ($requestLampiranFile) {
                    if (!empty($lampiranLamaDBVal) && file_exists(public_path('assets/po/pembayaran/' . $lampiranLamaDBVal))) {
                        unlink(public_path('assets/po/pembayaran/' . $lampiranLamaDBVal));
                    }

                    $ext = $requestLampiranFile->getClientOriginalExtension();
                    $namaLampiran = Str::random(25) . '.' . $ext;
                    $requestLampiranFile->move(public_path('assets/po/pembayaran/'), $namaLampiran);
                    $lampiranBaru = $namaLampiran;
                    $lampiranDipakai[] = $lampiranBaru;
                }

                InputPOPembayaran::create([
                    'id_po' => $data->id,
                    'tanggal' => $request->tanggal_pembayaran[$index],
                    'terbayar' => $terbayar,
                    'lampiran' => $lampiranBaru
                ]);

                Pengeluaran::create([
                    'id_po' => $data->id,
                    'id_bank' => $request->id_bank,
                    'tanggal' => $request->tanggal_pembayaran[$index],
                    'nominal' => $terbayar,
                    'id_kategori_transaksi' => 9,
                    'keterangan' => '',
                    'lampiran' => $lampiranBaru
                ]);

                $totalTerbayar += $terbayar;
            }

            foreach ($lampiranLamaDB as $lampiran) {
                if (!empty($lampiran) && !in_array($lampiran, $lampiranDipakai)) {
                    $filePath = public_path('assets/po/pembayaran/' . $lampiran);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            $data->terbayar = $totalTerbayar;

            $data->save();
            $this->logEdit('Input PO Pembayaran', $data->id);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui Pembayaran.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $data = InputPO::findOrFail($id);
        if (!empty($data->lampiran_po) && file_exists(public_path('assets/po/lampiran/' . $data->lampiran_po))) {
            unlink(public_path('assets/po/lampiran/' . $data->lampiran_po));
        }
        InputPODetail::where('id_po', $data->id)->delete();

        $dataBayar = InputPOPembayaran::where('id_po', $data->id)->get();
        foreach ($dataBayar as $bayar) {
            if (!empty($bayar->lampiran) && file_exists(public_path('assets/po/pembayaran/' . $bayar->lampiran))) {
                unlink(public_path('assets/po/pembayaran/' . $bayar->lampiran));
            }
            $bayar->delete();
        }

        $this->logDelete('Input PO', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
