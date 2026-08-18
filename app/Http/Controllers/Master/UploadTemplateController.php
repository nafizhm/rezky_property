<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Models\DocumentTemplate;
use App\Services\DocumentDataContext;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UploadTemplateController extends Controller
{
    use LogAktivitasTrait;

    public function index()
    {
        $permissions = HakAksesController::getUserPermissions();

        if (request()->ajax()) {
            $data = DocumentTemplate::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('engine', function ($row) {
                    $badges = [
                        'docx' => 'badge-primary',
                        'pdf'  => 'badge-danger',
                        'html' => 'badge-success',
                    ];
                    $class = $badges[$row->engine] ?? 'badge-secondary';
                    return '<span class="badge ' . $class . '">' . strtoupper($row->engine) . '</span>';
                })
                ->editColumn('is_active', function ($row) {
                    $class = $row->is_active ? 'badge-success' : 'badge-secondary';
                    $text  = $row->is_active ? 'Aktif' : 'Nonaktif';
                    return '<span class="badge ' . $class . '">' . $text . '</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $editUrl   = route('upload-template.edit', $row->id);
                    $deleteUrl = route('upload-template.destroy', $row->id);

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
                ->rawColumns(['engine', 'is_active', 'action'])
                ->make(true);
        }

        $contextKeys = DocumentDataContext::getContextKeys();

        return view('admin.master.upload_template.index', compact('permissions', 'contextKeys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'kode'        => 'required|string|max:50|unique:document_templates,kode',
            'deskripsi'   => 'nullable|string',
            'engine'      => 'required|in:docx,pdf,html',
            'file_template' => 'nullable|file|mimes:docx,pdf|max:5120',
            'konten'      => 'nullable|string',
            'is_active'   => 'boolean',
        ], [
            'nama.required'      => 'Nama template wajib diisi.',
            'kode.required'      => 'Kode template wajib diisi.',
            'kode.unique'        => 'Kode template sudah digunakan.',
            'engine.required'    => 'Jenis engine wajib dipilih.',
            'file_template.mimes' => 'File harus berupa DOCX atau PDF.',
            'file_template.max'  => 'Ukuran file maksimal 5MB.',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'nama'       => $request->nama,
                'kode'       => $request->kode,
                'deskripsi'  => $request->deskripsi,
                'engine'     => $request->engine,
                'konten'     => $request->engine === 'html' ? $request->konten : null,
                'is_active'  => $request->boolean('is_active', true),
            ];

            if ($request->hasFile('file_template')) {
                $file     = $request->file('file_template');
                $ext      = $file->getClientOriginalExtension();
                $filename = 'template_' . $request->kode . '_' . Str::random(8) . '.' . $ext;
                $file->move(public_path('document_templates'), $filename);
                $data['file_path'] = $filename;
            }

            $template = DocumentTemplate::create($data);
            $this->logCreate('DocumentTemplate', $template->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil disimpan.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan template.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = DocumentTemplate::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Template tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $template = DocumentTemplate::findOrFail($id);

        $request->validate([
            'nama'        => 'required|string|max:100',
            'kode'        => 'required|string|max:50|unique:document_templates,kode,' . $id,
            'deskripsi'   => 'nullable|string',
            'engine'      => 'required|in:docx,pdf,html',
            'file_template' => 'nullable|file|mimes:docx,pdf|max:5120',
            'konten'      => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'nama'       => $request->nama,
                'kode'       => $request->kode,
                'deskripsi'  => $request->deskripsi,
                'engine'     => $request->engine,
                'is_active'  => $request->boolean('is_active', true),
            ];

            if ($request->engine === 'html') {
                $data['konten'] = $request->konten;
            } else {
                $data['konten'] = null;
            }

            if ($request->hasFile('file_template')) {
                if ($template->file_path && file_exists(public_path('document_templates/' . $template->file_path))) {
                    unlink(public_path('document_templates/' . $template->file_path));
                }

                $file     = $request->file('file_template');
                $ext      = $file->getClientOriginalExtension();
                $filename = 'template_' . $request->kode . '_' . Str::random(8) . '.' . $ext;
                $file->move(public_path('document_templates'), $filename);
                $data['file_path'] = $filename;
            }

            $template->update($data);
            $this->logEdit('DocumentTemplate', $template->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil diperbarui.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui template.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $template = DocumentTemplate::findOrFail($id);

            if ($template->file_path && file_exists(public_path('document_templates/' . $template->file_path))) {
                unlink(public_path('document_templates/' . $template->file_path));
            }

            $this->logDelete('DocumentTemplate', $template->id);
            $template->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus template.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
