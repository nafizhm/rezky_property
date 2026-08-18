<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BarangKeluarController extends Controller
{
    use LogAktivitasTrait;

    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = BarangKeluar::orderBy('id', 'desc');

            $dataTable = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    $tanggal = Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                    $badge = '<div><span class="badge bg-primary">'.e($row->no_po).'</span></div>';

                    return $tanggal.$badge;
                })
                ->addColumn('jum_item', function ($row) {
                    return BarangKeluarDetail::where('id_barang_keluar', $row->id)->count();
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $showUrl = route('barang-keluar.show', $row->id);
                    $deleteUrl = route('barang-keluar.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($permissions['edit']) {
                        $btn .= '<a href="'.e($showUrl).'" class="btn btn-primary btn-sm mx-1">Edit</a>';
                    }
                    if ($permissions['hapus']) {
                        $btn .= '<form action="'.e($deleteUrl).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
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

        return view('admin.barang_keluar.index', compact('permissions'));
    }

    public function create()
    {
        $barangList = Barang::all();

        return view('admin.barang_keluar.create', compact('barangList'));
    }

    public function show(Request $request, string $id)
    {
        $dataBK = BarangKeluar::findOrFail($id);

        $barangList = Barang::all();

        $dataBKD = BarangKeluarDetail::where('id_barang_keluar', $dataBK->id)->get();

        return view('admin.barang_keluar.show', compact('dataBK', 'barangList', 'dataBKD'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'lampiran' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'barang' => 'required|array',
            'barang.*' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
        ];

        $messages = [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'lampiran.mimes' => 'Lampiran harus berupa jpg, jpeg, png, atau pdf.',
            'lampiran.max' => 'Ukuran lampiran maksimal 2MB.',
            'barang.required' => 'Barang wajib diisi.',
            'barang.array' => 'Data barang tidak valid.',
            'barang.*.required' => 'Barang harus diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.array' => 'Data jumlah tidak valid.',
            'jumlah.*.required' => 'Jumlah harus diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $ext = $file->getClientOriginalExtension();

                $lampiran = Str::random(25).'.'.$ext;
                $file->move(public_path('assets/barang_keluar/'), $lampiran);
            }

            $BarangKeluar = BarangKeluar::create([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan ?? '',
                'lampiran' => $lampiran ?? '',
            ]);
            $this->logCreate('Barang Keluar', $BarangKeluar->id);

            foreach ($request->barang as $index => $id_barang) {
                BarangKeluarDetail::create([
                    'id_barang_keluar' => $BarangKeluar->id,
                    'id_barang' => $id_barang,
                    'jumlah' => (int) str_replace('.', '', $request->jumlah)[$index],
                ]);
                $barang = Barang::findOrFail($id_barang);
                $barang->stok -= (int) str_replace('.', '', $request->jumlah)[$index];
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Barang Keluar berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan Barang Keluar.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Carbon::setLocale('id');

        $data = BarangKeluar::findOrFail($id);

        $rules = [
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'lampiran' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'barang' => 'required|array',
            'barang.*' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
        ];

        $messages = [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'lampiran.mimes' => 'Lampiran harus berupa jpg, jpeg, png, atau pdf.',
            'lampiran.max' => 'Ukuran lampiran maksimal 2MB.',
            'barang.required' => 'Barang wajib diisi.',
            'barang.array' => 'Data barang tidak valid.',
            'barang.*.required' => 'Barang harus diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.array' => 'Data jumlah tidak valid.',
            'jumlah.*.required' => 'Jumlah harus diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $lampiran = $data->lampiran;

            if ($request->hasFile('lampiran')) {
                if (! empty($data->lampiran) && file_exists(public_path('assets/barang_keluar/'.$data->lampiran))) {
                    unlink(public_path('assets/barang_keluar/'.$data->lampiran));
                }

                $file = $request->file('lampiran');
                $ext = $file->getClientOriginalExtension();

                $lampiran = Str::random(25).'.'.$ext;
                $file->move(public_path('assets/barang_keluar/'), $lampiran);
            }

            $data->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan ?? '',
                'lampiran' => $lampiran ?? '',
            ]);
            $this->logEdit('Barang Keluar', $data->id);

            $cekSO = BarangKeluarDetail::where('id_barang_keluar', $data->id)->exists();
            if ($cekSO) {
                $SOD = BarangKeluarDetail::where('id_barang_keluar', $data->id)->get();
                foreach ($SOD as $item) {
                    $barang = Barang::find($item->id_barang);
                    if ($barang) {
                        $barang->stok += $item->jumlah;
                        $barang->save();
                    }
                }
            }

            BarangKeluarDetail::where('id_barang_keluar', $data->id)->delete();

            foreach ($request->barang as $index => $id_barang) {
                BarangKeluarDetail::create([
                    'id_barang_keluar' => $data->id,
                    'id_barang' => $id_barang,
                    'jumlah' => (int) str_replace('.', '', $request->jumlah)[$index],
                ]);

                $barang = Barang::findOrFail($id_barang);
                $barang->stok -= (int) str_replace('.', '', $request->jumlah)[$index];
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Barang Keluar berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui Barang Keluar.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $data = BarangKeluar::findOrFail($id);
        if (! empty($data->lampiran) && file_exists(public_path('assets/barang_keluar/'.$data->lampiran))) {
            unlink(public_path('assets/barang_keluar/'.$data->lampiran));
        }
        $dataBKD = BarangKeluarDetail::where('id_barang_keluar', $data->id)->get();

        foreach ($dataBKD as $bkd) {
            $barang = Barang::findOrFail($bkd->id_barang);
            $barang->stok += $bkd->jumlah;
            $barang->save();
        }

        BarangKeluarDetail::where('id_barang_keluar', $data->id)->delete();

        $this->logDelete('Barang Keluar', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }

    public function validateJumlah(Request $request)
    {
        $request->validate([
            'barang' => 'required|integer',
            'jumlah' => 'required|integer',
        ]);

        $id_barang = Barang::where('id', $request->barang)->first();
        if (! $id_barang) {
            return response()->json(['valid' => false, 'message' => 'Barang tidak ditemukan.'], 404);
        }

        if ($request->jumlah > $id_barang->stok) {
            return response()->json([
                'valid' => false,
                'message' => 'Melebihi Stok ('.$id_barang->stok.')',
            ]);
        }

        return response()->json(['valid' => true]);
    }
}
