<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\Customer;
use App\Models\UploudFile;
use App\Traits\LogAktivitasTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UploudFileController extends Controller
{
    use LogAktivitasTrait;
    public function index()
    {
        $data        = UploudFile::first();
        $permissions = HakAksesController::getUserPermissions();
        $customer    = Customer::where('stt_arsip', 0)->get();

        return view('admin.customer.uploud_file.index', compact('data', 'permissions', 'customer'));
    }
    
    public function edit(Request $request, $id)
    {
        $nasabah = Customer::with(['lokasiKavling', 'kavlingPeta'])->find($id);

        if (! $nasabah) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Nasabah tidak ditemukan',
            ], 404);
        }

        if ($request->ajax() && $request->get('type') === 'files') {
            $data = UploudFile::where('id_customer', $id)->orderBy('id', 'desc');

            if ($data->count() === 0) {
                return response()->json(['data' => []]);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('lampiran', function ($row) {
                    return $row->lampiran;
                })
                ->addColumn('action', function ($row) {
                    $deleteUrl = route('upload-file.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">'
                    . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'status'           => 'success',
            'nik'              => $nasabah->nik,
            'lokasi_perumahan' => $nasabah->lokasiKavling ? $nasabah->lokasiKavling->nama_kavling : null,
            'no_telp'          => $nasabah->no_telp,
            'lokasi_kav_blok'  => $nasabah->kavlingPeta ? $nasabah->kavlingPeta->kode_kavling : null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_file' => 'required',
            'lampiran'  => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $messages = [
            'nama_file.required' => 'Nama file wajib diisi.',
            'lampiran.required'  => 'Lampiran wajib diisi.',

            'lampiran.mimes'     => 'Lampiran harus berformat JPG, JPEG, PNG, atau PDF.',
            'lampiran.max'       => 'Ukuran lampiran maksimal 2 MB.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {

            if ($request->hasFile('lampiran')) {
                $lampiran = $request->file('lampiran');
                $ext      = $lampiran->getClientOriginalExtension();
                $filename = Str::random(25) . '.' . $ext;
                $lampiran->move(public_path('assets/customer/'), $filename);
            }

            $db = [
                'tanggal'     => Carbon::now(),
                'lampiran'    => $filename,
                'id_customer' => $id,
                'nama_file'   => $request->nama_file,
                'keterangan'  => $request->keterangan ?? '',
            ];

            $up = UploudFile::create($db);
            $this->logEdit('Upload File', $up->id);

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
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
            $data = UploudFile::findOrFail($id);

            if (! empty($data->lampiran) && file_exists(public_path('assets/customer/' . $data->lampiran))) {
                unlink(public_path('assets/customer/' . $data->lampiran));
            }

            $this->logDelete('Upload File', $data->id);
            $data->delete();

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }
}
