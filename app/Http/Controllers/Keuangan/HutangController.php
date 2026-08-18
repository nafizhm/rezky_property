<?php
namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Bank;
use App\Models\Hutang;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class HutangController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Hutang::query()->orderByDesc('tanggal_hutang');

            if ($request->filled('filter_tanggal')) {
                $data->whereDate('tanggal_hutang', $request->filter_tanggal);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal_hutang', function ($row) {
                    return Carbon::parse($row->tanggal_hutang)->translatedFormat('j F Y');
                })
                ->editColumn('nominal', function ($row) {
                    return '
                    <div class="d-flex justify-content-between harga-format w-100">
                        <span>Rp.</span>
                        <span>' . number_format($row->nominal, 0, ',', '.') . '</span>
                    </div>';
                })
                ->addColumn('lampiran', function ($row) {
                    if ($row->lampiran) {
                        return '<button class="btn btn-sm btn-success show-lampiran" data-file="' . e($row->lampiran) . '" data-toggle="modal" data-target="#modallampiran">Lihat</button>';
                    }
                    return '-';
                })
                ->editColumn('status', function ($row) {
                    $badge = match ($row->status) {
                        1       => '<span class="badge bg-danger">Belum Lunas</span>',
                        2       => '<span class="badge bg-success">Sudah Lunas</span>',
                        default => '<span class="badge bg-warning">Status Tidak Dikenal</span>',
                    };

                    if ($row->status == 1) {
                        $sisaBayar = number_format($row->sisa_bayar, 0, ',', '.');
                        return $badge . '<br><small class="text-muted">Sisa Bayar: Rp. ' . $sisaBayar . '</small>';
                    }

                    return $badge;
                })
                ->addColumn('tgl_pelunasan', function ($row) {
                    return $row->tgl_pelunasan
                        ? Carbon::parse($row->tgl_pelunasan)->translatedFormat('j F Y')
                        : '';
                })
                ->filterColumn('tanggal_hutang', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->WhereDate('tanggal_hutang', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('hutang.edit', $row->id);
                    $deleteUrl = route('hutang.destroy', $row->id);

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
                ->rawColumns(['action', 'tanggal_hutang', 'nominal', 'status', 'tgl_bayar_hutang'])
                ->make(true);
        }

        $bankList = Bank::all();

        return view('admin.keuangan.hutang.index', compact('permissions', 'bankList'));
    }

    public function edit($id)
    {
        $list = Hutang::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_hutang' => 'required|date',
            'nominal'        => 'required',
            'id_bank'        => 'required',
            'lampiran'       => 'required|file|mimes:jpg,jpeg,png,pdf,webp|max:2048',
            'deskripsi'      => 'required',
        ], [
            'tanggal_hutang.required' => 'Tanggal hutang wajib diisi.',
            'tanggal_hutang.date'     => 'Tanggal hutang harus berupa tanggal.',
            'nominal.required'        => 'Nominal wajib diisi.',
            'id_bank.required'        => 'Bank wajib diisi.',
            'lampiran.required'       => 'Lampiran wajib diisi.',
            'lampiran.file'           => 'Lampiran harus berupa file.',
            'lampiran.mimes'          => 'Format lampiran harus jpg, jpeg, png, atau pdf.',
            'lampiran.max'            => 'Ukuran file maksimal 2MB.',
            'deskripsi.required'      => 'Deskripsi wajib diisi.',
        ]);

        DB::beginTransaction();
        try {

            $db = [
                'tanggal_hutang' => $request->tanggal_hutang,
                'deskripsi'      => $request->deskripsi,
                'nominal'        => str_replace('.', '', $request->nominal),
                'id_bank'        => $request->id_bank,
                'status'         => 1,
                'terbayar'       => 0,
                'sisa_bayar'     => str_replace('.', '', $request->nominal),
                'tgl_pelunasan'  => null,
            ];

            if ($request->hasFile('lampiran')) {
                $file     = $request->file('lampiran');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;

                $file->move(public_path('assets/keuangan/pemasukan/'), $filename);

                $db['lampiran'] = $filename;
            }

            $hutang = Hutang::create($db);
            $this->logCreate('Hutang', $hutang->id);

            Pemasukan::create([
                'id_hutang'             => $hutang->id,
                'tanggal'               => $request->tanggal_hutang,
                'nominal'               => str_replace('.', '', $request->nominal),
                'id_bank'               => $request->id_bank,
                'lampiran'              => $filename,
                'id_kategori_transaksi' => 6,
                'keterangan'            => $request->deskripsi,
            ]);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error Store Hutang:' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $data = Hutang::findOrFail($id);

        $rules = [
            'tanggal_hutang' => 'required|date',
            'nominal'        => 'required',
            'id_bank'        => 'required',
            'deskripsi'      => 'required',
        ];

        if (empty($data->lampiran)) {
            $rules['lampiran'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } elseif ($request->hasFile('lampiran')) {
            $rules['lampiran'] = 'file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        $request->validate($rules, [
            'tanggal_hutang.required' => 'Tanggal hutang wajib diisi.',
            'tanggal_hutang.date'     => 'Tanggal hutang harus berupa tanggal.',
            'nominal.required'        => 'Nominal wajib diisi.',
            'id_bank.required'        => 'Bank wajib diisi.',
            'lampiran.required'       => 'Lampiran wajib diisi.',
            'lampiran.file'           => 'Lampiran harus berupa file.',
            'lampiran.mimes'          => 'Format lampiran harus jpg, jpeg, png, atau pdf.',
            'lampiran.max'            => 'Ukuran file maksimal 2MB.',
            'deskripsi.required'      => 'Deskripsi wajib diisi.',
        ]);

        DB::beginTransaction();
        try {

            $db = [
                'tanggal_hutang' => $request->tanggal_hutang,
                'deskripsi'      => $request->deskripsi,
                'nominal'        => str_replace('.', '', $request->nominal),
                'id_bank'        => $request->id_bank,
                'terbayar'       => 0,
                'sisa_bayar'     => str_replace('.', '', $request->nominal),
                'tgl_pelunasan'  => null,
            ];

            $filename = $data->lampiran;

            if ($request->hasFile('lampiran')) {
                if (! empty($data->lampiran) && file_exists(public_path('assets/keuangan/pemasukan/' . $data->lampiran))) {
                    unlink(public_path('assets/keuangan/pemasukan/' . $data->lampiran));
                }

                $file     = $request->file('lampiran');
                $ext      = $file->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $file->move(public_path('assets/keuangan/pemasukan/'), $filename);

                $db['lampiran'] = $filename;
            }

            $data->update($db);
            $this->logEdit('Hutang', $data->id);

            $pemasukan = Pemasukan::where('id_hutang', $id)->first();
            $pemasukan->update([
                'tanggal'  => $request->tanggal_hutang,
                'nominal'  => str_replace('.', '', $request->nominal),
                'lampiran' => $filename,
            ]);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error Update Hutang:' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = Hutang::findOrFail($id);
            if (! empty($data->lampiran) && file_exists(public_path('assets/keuangan/pemasukan/' . $data->lampiran))) {
                unlink(public_path('assets/keuangan/pemasukan/' . $data->lampiran));
            }

            $pemasukan = Pemasukan::where('id_hutang', $id)->first();
            if ($pemasukan) {
                if (! empty($pemasukan->lampiran) && file_exists(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran))) {
                    unlink(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran));
                }
                $pemasukan->delete();
            }

            $pengeluaran = Pengeluaran::where('id_hutang', $id)->first();
            if ($pengeluaran) {
                if (! empty($pengeluaran->lampiran) && file_exists(public_path('assets/keuangan/pengeluaran/' . $pengeluaran->lampiran))) {
                    unlink(public_path('assets/keuangan/pengeluaran/' . $pengeluaran->lampiran));
                }
                $pengeluaran->delete();
            }

            $this->logDelete('Hutang', $data->id);
            $data->delete();

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error Delete Hutang:' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function getSisaBayar($id)
    {
        $hutang = Hutang::find($id);

        if (! $hutang) {
            return response()->json(['sisa_bayar' => 0], 404);
        }

        return response()->json(['sisa_bayar' => $hutang->sisa_bayar]);
    }
}
