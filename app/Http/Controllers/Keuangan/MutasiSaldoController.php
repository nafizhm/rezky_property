<?php
namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Bank;
use App\Models\MutasiSaldo;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MutasiSaldoController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = MutasiSaldo::orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('rekening_asal', function ($row) {
                    $bank = Bank::find($row->rekening_asal);
                    return $bank ? $bank->nama : 'Bank Tidak Diketahui';
                })
                ->addColumn('rekening_tujuan', function ($row) {
                    $bank = Bank::find($row->rekening_tujuan);
                    return $bank ? $bank->nama : 'Bank Tidak Diketahui';
                })
                ->addColumn('nominal', function ($row) {
                    return '
                    <div class="d-flex justify-content-between harga-format w-100">
                        <span>Rp.</span>
                        <span>' . number_format($row->nominal, 0, ',', '.') . '</span>
                    </div>';
                })
                ->addColumn('tanggal', function ($row) {
                    $tanggal = Carbon::parse($row->tanggal)->translatedFormat('d F Y');

                    return $tanggal;
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('mutasi-saldo.edit', $row->id);
                    $deleteUrl = route('mutasi-saldo.destroy', $row->id);

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

                ->rawColumns(['action', 'nominal'])
                ->make(true);
        }

        $bankList = Bank::all();

        return view('admin.keuangan.mutasi_saldo.index', compact('permissions', 'bankList'));
    }

    public function edit($id)
    {
        $list = MutasiSaldo::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'rekening_asal'   => 'required',
            'rekening_tujuan' => 'required|different:rekening_asal',
            'nominal'         => 'required',
            'lampiran'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan'      => 'nullable',
        ], [
            'tanggal.required'          => 'Tanggal harus diisi.',
            'tanggal.date'              => 'Format tanggal tidak valid.',
            'rekening_asal.required'    => 'Rekening asal harus diisi.',
            'rekening_tujuan.required'  => 'Rekening tujuan harus diisi.',
            'rekening_tujuan.different' => 'Rekening tujuan harus berbeda dengan rekening asal.',
            'nominal.required'          => 'Nominal harus diisi.',
            'lampiran.required'         => 'Lampiran harus diunggah.',
            'lampiran.file'             => 'Lampiran harus berupa file.',
            'lampiran.mimes'            => 'Format lampiran harus jpg, jpeg, png, atau pdf.',
            'lampiran.max'              => 'Ukuran lampiran maksimal 2MB.',
        ]);

        DB::beginTransaction();
        try {

            if ($request->hasFile('lampiran')) {
                $file     = $request->file('lampiran');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(20) . '.' . $ext;

                $pathMutasi      = public_path('assets/keuangan/mutasi_saldo/');
                $pathPemasukan   = public_path('assets/keuangan/pemasukan/');
                $pathPengeluaran = public_path('assets/keuangan/pengeluaran/');

                $file->move($pathMutasi, $filename);

                copy($pathMutasi . $filename, $pathPemasukan . $filename);
                copy($pathMutasi . $filename, $pathPengeluaran . $filename);
            }

            $nominal = (int) str_replace('.', '', $request->nominal);

            $ms = MutasiSaldo::create([
                'tanggal'         => $request->tanggal,
                'rekening_asal'   => $request->rekening_asal,
                'rekening_tujuan' => $request->rekening_tujuan,
                'nominal'         => $nominal,
                'keterangan'      => $request->keterangan ?? '',
                'lampiran'        => $filename,
            ]);
            $this->logCreate('Mutasi Saldo', $ms->id);

            Pemasukan::create([
                'id_mutasi'             => $ms->id,
                'id_bank'               => $request->rekening_tujuan,
                'tanggal'               => $request->tanggal,
                'nominal'               => $nominal,
                'id_kategori_transaksi' => 7,
                'keterangan'            => $request->keterangan ?? '',
                'lampiran'              => $filename,
            ]);

            Pengeluaran::create([
                'id_mutasi'             => $ms->id,
                'id_bank'               => $request->rekening_asal,
                'tanggal'               => $request->tanggal,
                'nominal'               => $nominal,
                'id_kategori_transaksi' => 8,
                'keterangan'            => $request->keterangan ?? '',
                'lampiran'              => $filename,
            ]);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::info('Mutasi Store Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = MutasiSaldo::findOrFail($id);

        $request->validate([
            'tanggal'         => 'required|date',
            'rekening_asal'   => 'required',
            'rekening_tujuan' => 'required|different:rekening_asal',
            'nominal'         => 'required',
            'lampiran'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('lampiran')) {
            if (! empty($data->lampiran) && file_exists(public_path('assets/keuangan/mutasi_saldo/' . $data->lampiran))) {
                unlink(public_path('assets/keuangan/mutasi_saldo/' . $data->lampiran));
                unlink(public_path('assets/keuangan/pemasukan/' . $data->lampiran));
                unlink(public_path('assets/keuangan/pengeluaran/' . $data->lampiran));
            }

            $file     = $request->file('lampiran');
            $ext      = $file->getClientOriginalExtension();
            $filename = Str::random(20) . '.' . $ext;
            $file->move(public_path('assets/keuangan/mutasi_saldo/'), $filename);
            $file->move(public_path('assets/keuangan/pemasukan/'), $filename);
            $file->move(public_path('assets/keuangan/pengeluaran/'), $filename);
        }

        $nominal = (int) str_replace('.', '', $request->nominal);

        $db = [
            'tanggal'         => $request->tanggal,
            'rekening_asal'   => $request->rekening_asal,
            'rekening_tujuan' => $request->rekening_tujuan,
            'nominal'         => $nominal,
            'keterangan'      => $request->keterangan ?? '',
            'lampiran'        => $filename ?? $data->lampiran,
        ];

        $this->logEdit('Mutasi Saldo', $data->id);
        $data->update($db);

        $pemasukan = Pemasukan::where('id_mutasi', $id)->first();
        if ($pemasukan) {
            $pemasukan->update([
                'id_bank'    => $request->rekening_tujuan,
                'tanggal'    => $request->tanggal,
                'nominal'    => $nominal,
                'keterangan' => $request->keterangan ?? '',
                'lampiran'   => $filename ?? $data->lampiran,
            ]);
        } else {
            Pemasukan::create([
                'id_mutasi'             => $id,
                'id_bank'               => $request->rekening_tujuan,
                'tanggal'               => $request->tanggal,
                'nominal'               => $nominal,
                'id_kategori_transaksi' => 7,
                'keterangan'            => $request->keterangan ?? '',
                'lampiran'              => $filename ?? $data->lampiran,
            ]);
        }

        $pengeluaran = Pengeluaran::where('id_mutasi', $id)->first();
        if ($pengeluaran) {
            $pengeluaran->update([
                'id_bank'    => $request->rekening_asal,
                'tanggal'    => $request->tanggal,
                'nominal'    => $nominal,
                'keterangan' => $request->keterangan ?? '',
                'lampiran'   => $filename ?? $data->lampiran,
            ]);
        } else {
            Pengeluaran::create([
                'id_mutasi'             => $id,
                'id_bank'               => $request->rekening_asal,
                'tanggal'               => $request->tanggal,
                'nominal'               => $nominal,
                'id_kategori_transaksi' => 8,
                'keterangan'            => $request->keterangan ?? '',
                'lampiran'              => $filename ?? $data->lampiran,
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = MutasiSaldo::findOrFail($id);
        if (! empty($data->lampiran) && file_exists(public_path('assets/keuangan/mutasi_saldo/' . $data->lampiran))) {
            unlink(public_path('assets/keuangan/mutasi_saldo/' . $data->lampiran));
        }

        $pengeluaran = Pengeluaran::where('id_mutasi', $id)->first();

        if ($pengeluaran) {
            if (! empty($pengeluaran->lampiran) && file_exists(public_path('assets/keuangan/pengeluaran/' . $pengeluaran->lampiran))) {
                unlink(public_path('assets/keuangan/pengeluaran/' . $pengeluaran->lampiran));
            }
            $pengeluaran->delete();
        }
        $pemasukan = Pemasukan::where('id_mutasi', $id)->first();

        if ($pemasukan) {
            if (! empty($pemasukan->lampiran) && file_exists(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran))) {
                unlink(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran));
            }
            $pemasukan->delete();
        }

        $this->logDelete('Mutasi Saldo', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
