<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UnitReadyController extends Controller
{
    use LogAktivitasTrait;

    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $data = KavlingPeta::with(['lokasi'])
                ->orderBy('id', 'asc');

            if ($request->status_filter) {
                $data->where('status_ready', $request->status_filter);
            }

            if ($request->perumahan_filter) {
                $data->where('id_lokasi', $request->perumahan_filter);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id_lokasi', function ($row) {
                    return $row->lokasi ? $row->lokasi->nama_kavling : '-';
                })
                ->addColumn('status_ready', function ($row) {
                    return $row->status_ready == 1 ? 'Belum Mulai' : ($row->status_ready == 2 ? 'Belum Ready' : 'Ready');
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $showUrl = route('unit-ready.show', $row->id);
                    $btn = '<div class="d-flex justify-content-center align-items-center gap-1">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="'.e($row->id).'" data-url="'.e($showUrl).'">Edit</button>';
                    }
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $lokasiList = LokasiKavling::all();

        return view('admin.unit_ready.index', compact('permissions', 'lokasiList'));
    }

    public function show($id)
    {
        $data = KavlingPeta::with(['lokasi', 'unitReady'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_lokasi' => 'required',
            'blok' => 'required',
            'unit_rumah' => 'required|array|min:1',
            'unit_rumah.*' => 'string',
            'jumlah_unit' => 'required|min:1',
        ];

        $messages = [
            'id_lokasi.required' => 'Lokasi wajib dipilih.',
            'blok.required' => 'Blok wajib dipilih.',
            'unit_rumah.required' => 'Unit Rumah wajib dipilih.',
            'unit_rumah.*.string' => 'Unit Rumah tidak valid.',
            'jumlah_unit.required' => 'Jumlah Unit wajib diisi.',
            'jumlah_unit.min' => 'Jumlah Unit tidak valid.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $ids = KavlingPeta::where('id_lokasi', $request->id_lokasi)
                ->whereIn('kode_kavling', $request->unit_rumah)
                ->pluck('id')
                ->toArray();

            if (empty($ids)) {
                throw new \Exception('Data kavling tidak ditemukan');
            }

            KavlingPeta::whereIn('id', $ids)->update([
                'status_ready' => $request->status_ready,
                'keterangan' => $request->keterangan ?? '',
            ]);

            $this->logEdit('Unit Ready', implode(',', $ids));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal update Unit Ready', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_edit' => 'required',
        ], [
            'status_edit.required' => 'Status wajib diisi.',
        ]);

        DB::beginTransaction();

        try {
            $data = KavlingPeta::findOrFail($id);

            $data->update([
                'status_ready' => $request->status_edit,
                'keterangan' => $request->keterangan_edit ?? '',
            ]);

            $this->logEdit('Unit Ready', $data->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal update Unit Ready', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data',
            ], 500);
        }
    }
}
