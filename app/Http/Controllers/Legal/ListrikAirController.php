<?php
namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\KavlingPeta;
use App\Models\ListrikAir;
use App\Models\LokasiKavling;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ListrikAirController extends Controller
{
    use LogAktivitasTrait;
    public function index(Request $request)
    {
        $permissions = HakAksesController::getUserPermissions();

        if ($request->ajax()) {
            $opd = ListrikAir::orderBy('id', 'desc');

            return DataTables::of($opd)
                ->addIndexColumn()
                ->addColumn('lokasi_rumah', function ($row) {
                    $lokasi  = LokasiKavling::find($row->id_lokasi);
                    $kavling = KavlingPeta::find($row->id_kavling);

                    $namaLokasi  = $lokasi ? $lokasi->nama_kavling : '-';
                    $kodeKavling = $kavling ? $kavling->kode_kavling : '-';

                    return '<div>' . e($namaLokasi) . '</div>' .
                    '<span class="badge badge-info">' . e($kodeKavling) . '</span>';
                })
                ->addColumn('action', function ($row) use ($permissions): string {
                    $editUrl   = route('listrik-air.edit', $row->id);
                    $deleteUrl = route('listrik-air.destroy', $row->id);

                    $btn = '<div class="d-flex justify-content-center">';
                    if ($permissions['edit']) {
                        $btn .= '<button class="btn btn-primary btn-sm edit-button" data-id="' . e($row->id) . '" data-url="' . e($editUrl) . '">Edit</button>';
                    }

                    if ($permissions['hapus']) {
                        $btn .= '<form action="' . e($deleteUrl) . '" method="POST" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="delete-button btn btn-danger btn-sm ml-2">Hapus</button></form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['lokasi_rumah', 'action'])
                ->make(true);
        }
        $lokasi = LokasiKavling::all();

        return view('admin.legal.listrik_air.index', compact('permissions', 'lokasi'));
    }

    public function edit($id)
    {
        $list = ListrikAir::findOrFail($id);

        return response()->json([
            'status'             => 'success',
            'data'               => $list,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_lokasi'      => 'required',
            'id_kavling'     => 'required',
            'norek_listrik'  => 'required',
            'norek_air'      => 'required',
            'foto_listrik'   => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'foto_listrik_2' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'foto_air'       => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'foto_air_2'     => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ], [
            'id_lokasi.required'      => 'Lokasi perumahan wajib diisi.',
            'id_kavling.required'     => 'Blok/Kavling wajib diisi.',
            'norek_listrik.required'  => 'No. rekening listrik wajib diisi.',
            'norek_air.required'      => 'No. rekening air wajib diisi.',
            'foto_listrik.required'   => 'Foto meteran listrik wajib diunggah.',
            'foto_listrik.file'       => 'Foto meteran listrik harus berupa file yang valid.',
            'foto_listrik.mimes'      => 'Foto meteran listrik harus berformat jpeg, png, atau jpg.',
            'foto_listrik.max'        => 'Ukuran foto meteran listrik maksimal 2MB.',
            'foto_listrik_2.required' => 'Foto meteran listrik 2 wajib diunggah.',
            'foto_listrik_2.file'     => 'Foto meteran listrik 2 harus berupa file yang valid.',
            'foto_listrik_2.mimes'    => 'Foto meteran listrik 2 harus berformat jpeg, png, atau jpg.',
            'foto_listrik_2.max'      => 'Ukuran foto meteran listrik 2 maksimal 2MB.',
            'foto_air.required'       => 'Foto meteran air wajib diunggah.',
            'foto_air.file'           => 'Foto meteran air harus berupa file yang valid.',
            'foto_air.mimes'          => 'Foto meteran air harus berformat jpeg, png, atau jpg.',
            'foto_air.max'            => 'Ukuran foto meteran air maksimal 2MB.',
            'foto_air_2.required'     => 'Foto meteran air 2 wajib diunggah.',
            'foto_air_2.file'         => 'Foto meteran air 2 harus berupa file yang valid.',
            'foto_air_2.mimes'        => 'Foto meteran air 2 harus berformat jpeg, png, atau jpg.',
            'foto_air_2.max'          => 'Ukuran foto meteran air 2 maksimal 2MB.',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto_listrik')) {
                $ext             = $request->file('foto_listrik')->getClientOriginalExtension();
                $fotolistrikName = Str::random(25) . '.' . $ext;
                $request->file('foto_listrik')->move(public_path('assets/legal/listrik_air/listrik_1/'), $fotolistrikName);
            }

            if ($request->hasFile('foto_listrik_2')) {
                $ext              = $request->file('foto_listrik_2')->getClientOriginalExtension();
                $fotolistrik2Name = Str::random(25) . '.' . $ext;
                $request->file('foto_listrik_2')->move(public_path('assets/legal/listrik_air/listrik_2/'), $fotolistrik2Name);
            }

            if ($request->hasFile('foto_air')) {
                $ext         = $request->file('foto_air')->getClientOriginalExtension();
                $fotoairName = Str::random(25) . '.' . $ext;
                $request->file('foto_air')->move(public_path('assets/legal/listrik_air/air_1/'), $fotoairName);
            }

            if ($request->hasFile('foto_air_2')) {
                $ext          = $request->file('foto_air_2')->getClientOriginalExtension();
                $fotoair2Name = Str::random(25) . '.' . $ext;
                $request->file('foto_air_2')->move(public_path('assets/legal/listrik_air/air_2/'), $fotoair2Name);
            }

            $db = [
                'id_lokasi'      => $request->id_lokasi,
                'id_kavling'     => $request->id_kavling,
                'norek_listrik'  => $request->norek_listrik,
                'norek_air'      => $request->norek_air,
                'foto_listrik'   => $fotolistrikName,
                'foto_listrik_2' => $fotolistrik2Name,
                'foto_air'       => $fotoairName,
                'foto_air_2'     => $fotoair2Name,
            ];

            $la = ListrikAir::create($db);
            $this->logCreate('Listrik Air', $la->id);

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

    public function update(Request $request, $id)
    {
        $data = ListrikAir::findOrFail($id);

        $request->validate([
            'id_lokasi'      => 'required',
            'id_kavling'     => 'required',
            'norek_listrik'  => 'required',
            'norek_air'      => 'required',
            'foto_listrik'   => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'foto_listrik_2' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'foto_air'       => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'foto_air_2'     => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ], [
            'id_lokasi.required'     => 'Lokasi perumahan wajib diisi.',
            'id_kavling.required'    => 'Blok/Kavling wajib diisi.',
            'norek_listrik.required' => 'No. rekening listrik wajib diisi.',
            'norek_air.required'     => 'No. rekening air wajib diisi.',
            'foto_listrik.file'      => 'Foto meteran listrik harus berupa file yang valid.',
            'foto_listrik.mimes'     => 'Foto meteran listrik harus berformat jpeg, png, atau jpg.',
            'foto_listrik.max'       => 'Ukuran foto meteran listrik maksimal 2MB.',
            'foto_listrik_2.file'    => 'Foto meteran listrik 2 harus berupa file yang valid.',
            'foto_listrik_2.mimes'   => 'Foto meteran listrik 2 harus berformat jpeg, png, atau jpg.',
            'foto_listrik_2.max'     => 'Ukuran foto meteran listrik 2 maksimal 2MB.',
            'foto_air.file'          => 'Foto meteran air harus berupa file yang valid.',
            'foto_air.mimes'         => 'Foto meteran air harus berformat jpeg, png, atau jpg.',
            'foto_air.max'           => 'Ukuran foto meteran air maksimal 2MB.',
            'foto_air_2.file'        => 'Foto meteran air 2 harus berupa file yang valid.',
            'foto_air_2.mimes'       => 'Foto meteran air 2 harus berformat jpeg, png, atau jpg.',
            'foto_air_2.max'         => 'Ukuran foto meteran air 2 maksimal 2MB.',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto_listrik')) {
                if (! empty($data->foto_listrik) && file_exists(public_path('assets/legal/listrik_air/listrik_1/' . $data->foto_listrik))) {
                    unlink(public_path('assets/legal/listrik_air/listrik_1/' . $data->foto_listrik));
                }

                $foto            = $request->file('foto_listrik');
                $ext             = $foto->getClientOriginalExtension();
                $fotolistrikName = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/legal/listrik_air/listrik_1/'), $fotolistrikName);
            }

            if ($request->hasFile('foto_listrik_2')) {
                if (! empty($data->foto_listrik_2) && file_exists(public_path('assets/legal/listrik_air/listrik_2/' . $data->foto_listrik_2))) {
                    unlink(public_path('assets/legal/listrik_air/listrik_2/' . $data->foto_listrik_2));
                }

                $foto             = $request->file('foto_listrik_2');
                $ext              = $foto->getClientOriginalExtension();
                $fotolistrik2Name = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/legal/listrik_air/listrik_2/'), $fotolistrik2Name);
            }

            if ($request->hasFile('foto_air')) {
                if (! empty($data->foto_air) && file_exists(public_path('assets/legal/listrik_air/air_1/' . $data->foto_air))) {
                    unlink(public_path('assets/legal/listrik_air/air_1/' . $data->foto_air));
                }

                $foto        = $request->file('foto_air');
                $ext         = $foto->getClientOriginalExtension();
                $fotoairName = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/legal/listrik_air/air_1/'), $fotoairName);
            }

            if ($request->hasFile('foto_air_2')) {
                if (! empty($data->foto_air_2) && file_exists(public_path('assets/legal/listrik_air/air_2/' . $data->foto_air_2))) {
                    unlink(public_path('assets/legal/listrik_air/air_2/' . $data->foto_air_2));
                }

                $foto         = $request->file('foto_air_2');
                $ext          = $foto->getClientOriginalExtension();
                $fotoair2Name = Str::random(25) . '.' . $ext;
                $foto->move(public_path('assets/legal/listrik_air/air_2/'), $fotoair2Name);
            }

            $data->update([
                'id_lokasi'      => $request->id_lokasi,
                'id_kavling'     => $request->id_kavling,
                'norek_listrik'  => $request->norek_listrik,
                'norek_air'      => $request->norek_air,
                'foto_listrik'   => isset($fotolistrikName) ? $fotolistrikName : $data->foto_listrik,
                'foto_listrik_2' => isset($fotolistrik2Name) ? $fotolistrik2Name : $data->foto_listrik_2,
                'foto_air'       => isset($fotoairName) ? $fotoairName : $data->foto_air,
                'foto_air_2'     => isset($fotoair2Name) ? $fotoair2Name : $data->foto_air_2,
            ]);
            $this->logEdit('Listrik Air', $data->id);

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
        $data = ListrikAir::findOrFail($id);
        if (! empty($data->foto_listrik) && file_exists(public_path('assets/legal/listrik_air/listrik_1/' . $data->foto_listrik))) {
            unlink(public_path('assets/legal/listrik_air/listrik_1/' . $data->foto_listrik));
        }
        if (! empty($data->foto_listrik_2) && file_exists(public_path('assets/legal/listrik_air/listrik_2/' . $data->foto_listrik_2))) {
            unlink(public_path('assets/legal/listrik_air/listrik_2/' . $data->foto_listrik_2));
        }
        if (! empty($data->foto_air) && file_exists(public_path('assets/legal/listrik_air/air_1/' . $data->foto_air))) {
            unlink(public_path('assets/legal/listrik_air/air_1/' . $data->foto_air));
        }
        if (! empty($data->foto_air_2) && file_exists(public_path('assets/legal/listrik_air/air_2/' . $data->foto_air_2))) {
            unlink(public_path('assets/legal/listrik_air/air_2/' . $data->foto_air_2));
        }

        $this->logDelete('Listrik Air', $data->id);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
