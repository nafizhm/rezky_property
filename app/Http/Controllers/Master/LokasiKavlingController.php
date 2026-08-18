<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\BAST;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\LokasiKavlingPerusahaan;
use App\Models\Pemasukan;
use App\Models\Perusahaan;
use App\Models\PPJB;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LokasiKavlingController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = LokasiKavling::with(['kavlingPeta', 'perusahaan']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jumlah_kavling', function ($row) {
                    return $row->KavlingPeta->count();
                })
                ->editColumn('nama_kavling', function ($row) {
                    $field = '<strong>' . $row->nama_kavling . '</strong>';

                    foreach ($row->perusahaan as $p) {
                        $field .= '<br><span>' . $p->nama_perusahaan . '</span>';
                    }

                    return $field;
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('lokasi-kavling.edit', $row->id);
                    $deleteUrl = route('lokasi-kavling.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<a href="' . route('kavling.index', ['id_lokasi' => $row->id]) . '"
                            class="btn btn-xs btn-info mr-1">
                            Detail Kavling
                            </a>';

                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-xs mx-1 edit-button"
                                data-id="' . e($row->id) . '"
                                data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="delete-button btn btn-danger btn-xs mx-1">
                        Hapus
                    </button>
                    </form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['nama_kavling', 'jumlah_kavling', 'action'])
                ->make(true);
        }

        $perusahaanList = Perusahaan::all();

        return view('admin.master.lokasi_perumahan.index', compact('permissions', 'perusahaanList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_kavling'    => 'required',
            'nama_singkat'    => 'required',
            'alamat'          => 'required',
            'header'          => 'required',
            'urutan'          => 'required',
            'stt_tampil'      => 'required',
            'is_cluster'      => 'required',
            'id_perusahaan'   => 'required|array',
            'id_perusahaan.*' => 'required',
            'no_kwitansi'     => 'required',
            'no_bast'         => 'required',
            'no_ppjb'         => 'required',
            'reset_nomor'     => 'required',
        ];

        $messages = [
            'nama_kavling.required'  => 'Nama kavling wajib diisi.',
            'nama_singkat.required'  => 'Nama singkat wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'header.required'        => 'Header wajib diisi.',
            'urutan.required'        => 'Urutan wajib diisi.',
            'stt_tampil.required'    => 'Status tampil wajib diisi.',
            'is_cluster.required'    => 'Cluster wajib diisi.',
            'id_perusahaan.required' => 'Perusahaan wajib dipilih.',
            'no_kwitansi.required'   => 'No Kwitansi wajib diisi.',
            'no_bast.required'       => 'No BAST wajib diisi.',
            'no_ppjb.required'       => 'No PPJB wajib diisi.',
            'reset_nomor.required'   => 'Reset Nomor wajib diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $lokasi = LokasiKavling::create([
                'nama_kavling' => $request->nama_kavling,
                'nama_singkat' => $request->nama_singkat,
                'alamat'       => $request->alamat,
                'urutan'       => $request->urutan,
                'header'       => $request->header,
                'stt_tampil'   => $request->stt_tampil,
                'is_cluster'   => $request->is_cluster,
                'no_kwitansi'  => $request->no_kwitansi,
                'no_bast'      => $request->no_bast,
                'no_ppjb'      => $request->no_ppjb,
                'reset_nomor'  => $request->reset_nomor,
            ]);
            $this->logCreate('Lokasi Perumahan', $lokasi->id);

            foreach ($request->id_perusahaan as $perusahaan) {
                LokasiKavlingPerusahaan::create([
                    'id_lokasi'     => $lokasi->id,
                    'id_perusahaan' => $perusahaan,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = LokasiKavling::with('perusahaan')->find($id);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_kavling'    => 'required',
            'nama_singkat'    => 'required',
            'alamat'          => 'required',
            'header'          => 'required',
            'urutan'          => 'required',
            'stt_tampil'      => 'required',
            'is_cluster'      => 'required',
            'id_perusahaan'   => 'required|array',
            'id_perusahaan.*' => 'required',
            'no_kwitansi'     => 'required',
            'no_bast'         => 'required',
            'no_ppjb'         => 'required',
            'reset_nomor'     => 'required',
        ];

        $messages = [
            'nama_kavling.required'  => 'Nama kavling wajib diisi.',
            'nama_singkat.required'  => 'Nama singkat wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'header.required'        => 'Header wajib diisi.',
            'urutan.required'        => 'Urutan wajib diisi.',
            'stt_tampil.required'    => 'Status tampil wajib diisi.',
            'is_cluster.required'    => 'Cluster wajib diisi.',
            'id_perusahaan.required' => 'Perusahaan wajib dipilih.',
            'no_kwitansi.required'   => 'No Kwitansi wajib diisi.',
            'no_bast.required'       => 'No BAST wajib diisi.',
            'no_ppjb.required'       => 'No PPJB wajib diisi.',
            'reset_nomor.required'   => 'Reset Nomor wajib diisi.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            $lokasi = LokasiKavling::findOrFail($id);

            $oldNoKwitansi = $lokasi->no_kwitansi;
            $oldNoBast     = $lokasi->no_bast;
            $oldNoPpjb     = $lokasi->no_ppjb;

            $lokasi->update([
                'nama_kavling' => $request->nama_kavling,
                'nama_singkat' => $request->nama_singkat,
                'alamat'       => $request->alamat,
                'urutan'       => $request->urutan,
                'header'       => $request->header,
                'stt_tampil'   => $request->stt_tampil,
                'is_cluster'   => $request->is_cluster,
                'no_kwitansi'  => $request->no_kwitansi,
                'no_bast'      => $request->no_bast,
                'no_ppjb'      => $request->no_ppjb,
                'reset_nomor'  => $request->reset_nomor,
            ]);

            $this->logEdit('Lokasi Perumahan', $lokasi->id);

            if ($oldNoKwitansi !== $request->no_kwitansi) {
                $oldPattern = str_replace(['0000/', '/MM/YYYY'], '', $oldNoKwitansi);
                $newPattern = str_replace(['0000/', '/MM/YYYY'], '', $request->no_kwitansi);

                Pemasukan::whereHas('customer', function ($q) use ($lokasi) {
                    $q->where('id_lokasi', $lokasi->id);
                })->whereNotNull('no_kwitansi')
                    ->update([
                        'no_kwitansi' => DB::raw("REPLACE(no_kwitansi, '{$oldPattern}', '{$newPattern}')"),
                    ]);
            }

            if ($oldNoBast !== $request->no_bast) {
                $oldPattern = str_replace(['0000/', '/MM/YYYY'], '', $oldNoBast);
                $newPattern = str_replace(['0000/', '/MM/YYYY'], '', $request->no_bast);

                BAST::whereHas('customer', function ($q) use ($lokasi) {
                    $q->where('id_lokasi', $lokasi->id);
                })->update([
                    'no_bast' => DB::raw("REPLACE(no_bast, '{$oldPattern}', '{$newPattern}')"),
                ]);
            }

            if ($oldNoPpjb !== $request->no_ppjb) {
                $oldPattern = str_replace(['0000/', '/MM/YYYY'], '', $oldNoPpjb);
                $newPattern = str_replace(['0000/', '/MM/YYYY'], '', $request->no_ppjb);

                PPJB::whereHas('customer', function ($q) use ($lokasi) {
                    $q->where('id_lokasi', $lokasi->id);
                })->update([
                    'no_ppjb' => DB::raw("REPLACE(no_ppjb, '{$oldPattern}', '{$newPattern}')"),
                ]);
            }

            LokasiKavlingPerusahaan::where('id_lokasi', $lokasi->id)->delete();

            foreach ($request->id_perusahaan as $perusahaan) {
                LokasiKavlingPerusahaan::create([
                    'id_lokasi'     => $lokasi->id,
                    'id_perusahaan' => $perusahaan,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id_lokasi)
    {
        try {
            DB::beginTransaction();

            $lokasi = LokasiKavling::find($id_lokasi);

            LokasiKavlingPerusahaan::where('id_lokasi', $id_lokasi)->delete();

            $this->logDelete('Lokasi Perumahan', $lokasi->id);
            $lokasi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
