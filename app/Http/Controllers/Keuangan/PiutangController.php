<?php
namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Bank;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Piutang;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PiutangController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = Piutang::query()->orderByDesc('tanggal_piutang');

            if ($request->filled('filter_tanggal')) {
                $data->whereDate('tanggal_piutang', $request->filter_tanggal);
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal_piutang', function ($row) {
                    return Carbon::parse($row->tanggal_piutang)->translatedFormat('j F Y');
                })
                ->editColumn('nominal', function ($row) {
                    return '
                    <div class="d-flex justify-content-between harga-format w-100">
                        <span>Rp.</span>
                        <span>' . number_format($row->nominal, 0, ',', '.') . '</span>
                    </div>';
                })
                ->editColumn('status', function ($row) {
                    switch ($row->status) {
                        case 1:
                            return '<span class="badge bg-danger">Belum Lunas</span>';
                        case 2:
                            return '<span class="badge bg-success">Sudah Lunas</span>';
                        default:
                            return '<span class="badge bg-warning">Status Tidak Dikenal</span>';
                    }
                })
                ->addColumn('lampiran', function ($row) {
                    if ($row->lampiran) {
                        return '<button class="btn btn-sm btn-success show-lampiran" data-file="' . e($row->lampiran) . '" data-toggle="modal" data-target="#modallampiran">Lihat</button>';
                    }
                    return '-';
                })
                ->addColumn('tgl_pelunasan', function ($row) {
                    return $row->tgl_pelunasan
                        ? Carbon::parse($row->tgl_pelunasan)->translatedFormat('j F Y')
                        : '';
                })
                ->filterColumn('tanggal_piutang', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->WhereDate('tanggal_piutang', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('piutang.edit', $row->id);
                    $detailUrl = route('piutang.show', $row->id);
                    $deleteUrl = route('piutang.destroy', $row->id);

                    $isDetailOnly = $row->id_customer != 0;

                    $btn = '<div class="d-flex justify-content-center">';

                    if ($isDetailOnly) {
                        $btn .= '<button class="btn btn-primary btn-sm mx-1 detail-button"
            data-id="' . e($row->id) . '"
            data-url="' . e($detailUrl) . '">
            Detail
        </button>';
                    } else {
                        if ($permissions['edit']) {
                            $btn .= '<button class="btn btn-primary btn-sm mx-1 edit-button"
                data-id="' . e($row->id) . '"
                data-url="' . e($editUrl) . '">
                Edit
            </button>';
                        }
                    }

                    if ($permissions['hapus']) {
                        if ($isDetailOnly) {
                            $btn .= '<button class="btn btn-danger btn-sm mx-1" disabled>
                Hapus
            </button>';
                        } else {
                            $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                ' . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="delete-button btn btn-danger btn-sm mx-1">
                    Hapus
                </button>
            </form>';
                        }
                    }

                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['action', 'tanggal_piutang', 'nominal', 'status', 'tgl_bayar_hutang'])
                ->make(true);
        }

        $bankList = Bank::all();

        return view('admin.keuangan.piutang.index', compact('permissions', 'bankList'));
    }

    public function edit($id)
    {
        $list = Piutang::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }
    public function show($id)
    {
        $list = Piutang::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_piutang' => 'required|date',
            'nominal'         => 'required',
            'id_bank'         => 'required',
            'deskripsi'       => 'required',
            'lampiran'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'tanggal_piutang.required' => 'Tanggal piutang wajib diisi.',
            'tanggal_piutang.date'     => 'Tanggal piutang harus berupa tanggal.',
            'nominal.required'         => 'Nominal wajib diisi.',
            'id_bank.required'         => 'Bank wajib diisi.',
            'deskripsi.required'       => 'Deskripsi wajib diisi.',
            'lampiran.required'        => 'Lampiran wajib diisi.',
            'lampiran.file'            => 'Lampiran harus berupa file.',
            'lampiran.mimes'           => 'Format lampiran harus jpg, jpeg, png, atau pdf.',
            'lampiran.max'             => 'Ukuran file maksimal 2MB.',
        ]);

        $db = [
            'id_bank'         => $request->id_bank,
            'tanggal_piutang' => $request->tanggal_piutang,
            'deskripsi'       => $request->deskripsi,
            'nominal'         => str_replace('.', '', $request->nominal),
            'lampiran'        => '',
            'status'          => 1,
            'terbayar'        => 0,
            'sisa_bayar'      => str_replace('.', '', $request->nominal),
            'tgl_pelunasan'   => null,
        ];

        if ($request->hasFile('lampiran')) {
            $file     = $request->file('lampiran');
            $ext      = $file->getClientOriginalExtension();
            $filename = Str::random(25) . '.' . $ext;

            $file->move(public_path('assets/keuangan/pengeluaran/'), $filename);

            $db['lampiran'] = $filename;
        }

        $piutang = Piutang::create($db);
        $this->logCreate('Piutang', $piutang->id);

        Pengeluaran::create([
            'id_bank'               => $request->id_bank,
            'id_piutang'            => $piutang->id,
            'tanggal'               => $request->tanggal_piutang,
            'nominal'               => str_replace('.', '', $request->nominal),
            'lampiran'              => $filename,
            'id_kategori_transaksi' => 11,
            'keterangan'            => $request->deskripsi,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $data = Piutang::findOrFail($id);

        $rules = [
            'tanggal_piutang' => 'required|date',
            'nominal'         => 'required',
            'id_bank'         => 'required',
            'deskripsi'       => 'required',
        ];

        if (empty($data->lampiran)) {
            $rules['lampiran'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } elseif ($request->hasFile('lampiran')) {
            $rules['lampiran'] = 'file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        $request->validate($rules, [
            'tanggal_piutang.required' => 'Tanggal Piutang wajib diisi.',
            'tanggal_piutang.date'     => 'Tanggal Piutang harus berupa tanggal.',
            'nominal.required'         => 'Nominal wajib diisi.',
            'id_bank.required'         => 'Bank wajib diisi.',
            'lampiran.required'        => 'Lampiran wajib diisi.',
            'lampiran.file'            => 'Lampiran harus berupa file.',
            'lampiran.mimes'           => 'Format lampiran harus jpg, jpeg, png, atau pdf.',
            'lampiran.max'             => 'Ukuran file maksimal 2MB.',
            'deskripsi.required'       => 'Deskripsi wajib diisi.',
        ]);

        $db = [
            'tanggal_piutang' => $request->tanggal_piutang,
            'deskripsi'       => $request->deskripsi,
            'id_bank'         => $request->id_bank,
            'nominal'         => str_replace('.', '', $request->nominal),
            'sisa_bayar'      => str_replace('.', '', $request->nominal),
        ];

        $filename = $data->lampiran;

        if ($request->hasFile('lampiran')) {
            if (! empty($data->lampiran) && file_exists(public_path('assets/keuangan/pengeluaran/' . $data->lampiran))) {
                unlink(public_path('assets/keuangan/pengeluaran/' . $data->lampiran));
            }

            $file     = $request->file('lampiran');
            $ext      = $file->getClientOriginalExtension();
            $filename = Str::random(25) . '.' . $ext;
            $file->move(public_path('assets/keuangan/pengeluaran/'), $filename);

            $db['lampiran'] = $filename;
        }

        $data->update($db);
        $this->logEdit('Piutang', $data->id);

        $pengeluaran = Pengeluaran::where('id_piutang', $id)->first();
        $pengeluaran->update([
            'tanggal'  => $request->tanggal_hutang,
            'id_bank'  => $request->id_bank,
            'nominal'  => str_replace('.', '', $request->nominal),
            'lampiran' => $filename,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $data = Piutang::findOrFail($id);
        if (! empty($data->lampiran) && file_exists(public_path('assets/keuangan/pengeluaran/' . $data->lampiran))) {
            unlink(public_path('assets/keuangan/pengeluaran/' . $data->lampiran));
        }

        $pengeluaran = Pengeluaran::where('id_piutang', $id)->first();

        if ($pengeluaran) {
            if (! empty($pengeluaran->lampiran) && file_exists(public_path('assets/keuangan/pengeluaran/' . $pengeluaran->lampiran))) {
                unlink(public_path('assets/keuangan/pengeluaran/' . $pengeluaran->lampiran));
            }
            $pengeluaran->delete();
        }
        $pemasukan = Pemasukan::where('id_piutang', $id)->first();

        if ($pemasukan) {
            if (! empty($pemasukan->lampiran) && file_exists(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran))) {
                unlink(public_path('assets/keuangan/pemasukan/' . $pemasukan->lampiran));
            }
            $pemasukan->delete();
        }

        $this->logDelete('Piutang', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }

    public function getSisaBayar($id)
    {
        $hutang = Piutang::find($id);

        if (! $hutang) {
            return response()->json(['sisa_bayar' => 0], 404);
        }

        return response()->json(['sisa_bayar' => $hutang->sisa_bayar]);
    }
}
