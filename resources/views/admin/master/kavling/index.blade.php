@extends('admin.layout_admin')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-3">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Data Kavling</h3>
                                    <div class="d-flex align-items-center">
                                        <a id="btnPdf" href="{{ route('kavling.cetakPdf', ['id_lokasi' => '__ID__']) }}"
                                            target="_blank" class="btn btn-danger btn-sm mr-1">
                                            <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                                        </a>

                                        <a id="btnExcel"
                                            href="{{ route('kavling.cetakExcel', ['id_lokasi' => '__ID__']) }}"
                                            target="_blank" class="btn btn-success btn-sm mr-1">
                                            <i class="fas fa-file-excel mr-1"></i> Cetak Excel
                                        </a>

                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target="#modalImport">
                                            <i class="fas fa-file-import mr-1"></i> Import Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>BERHASIL!</strong>
                                        <div style="white-space: pre-wrap;">{{ session('success') }}</div>
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>ERROR!</strong>
                                        <div style="white-space: pre-wrap;">{{ $errors->first() }}</div>
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    </div>
                                @endif
                                <div class="row mb-4">
                                    <label class="col-sm-1 col-form-label">Lokasi</label>
                                    <div class="col-sm-3">
                                        <select name="id_lokasi" id="id_lokasi" class="form-control select-lokasi">
                                            <option value="0">Semua</option>
                                            @foreach ($lokasiList as $l)
                                                <option value="{{ $l->id }}"
                                                    {{ request('id_lokasi') == $l->id ? 'selected' : '' }}>
                                                    {{ $l->nama_kavling }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped small data-table w-100">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Lokasi / Kavling</th>
                                            <th width="130px">Panjang</th>
                                            <th width="130px">Lebar</th>
                                            <th width="130px">Luas</th>
                                            <th>Rincian Harga</th>
                                            <th>Total Harga</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Perumahan</label>
                            <div class="col-sm-4">
                                <input type="text" name="nama_kavling" id="nama_kavling" class="form-control" readonly>
                            </div>
                            <label class="col-sm-2 col-form-label">Kode Kavling</label>
                            <div class="col-sm-3">
                                <input type="text" name="kode_kavling" id="kode_kavling" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Panjang Kanan</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="panjang_kanan" id="panjang_kanan"
                                        class="form-control format-decimal">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m</span>
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-2 col-form-label">Panjang Kiri</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="panjang_kiri" id="panjang_kiri"
                                        class="form-control format-decimal">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lebar Depan</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="lebar_depan" id="lebar_depan"
                                        class="form-control format-decimal">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m</span>
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-2 col-form-label">Lebar Belakang</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="lebar_belakang" id="lebar_belakang"
                                        class="form-control format-decimal">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Luas Tanah</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="luas_tanah" id="luas_tanah"
                                        class="form-control format-decimal">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m²</span>
                                    </div>
                                </div>
                            </div>
                            <label class="col-sm-2 col-form-label">Luas Bangunan</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="luas_bangunan" id="luas_bangunan"
                                        class="form-control format-decimal">
                                    <div class="input-group-append">
                                        <span class="input-group-text">m²</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Harga per Meter</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" name="hrg_meter" id="hrg_meter"
                                        class="form-control format-number">
                                </div>
                            </div>
                            <label class="col-sm-2 col-form-label">Tipe Rumah</label>
                            <div class="col-sm-2">
                                <input type="text" name="tipe_bangunan" id="tipe_bangunan"
                                    class="form-control format-number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Rincian Biaya</label>
                            <div class="col-sm-9">
                                <div id="dynamic-biaya-container">
                                    <div class="text-muted small">Klik "Tambah Biaya" untuk menambahkan item biaya.</div>
                                </div>
                                <button type="button" id="tambahBiayaBtn" class="btn btn-success btn-sm mt-2">
                                    <i class="fas fa-plus mr-1"></i> Tambah Biaya
                                </button>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold">Total Harga</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" id="total_harga_display"
                                        class="form-control font-weight-bold" readonly style="background:#e9ecef">
                                </div>
                            </div>
                            <label class="col-sm-2 col-form-label">Daya Listrik</label>
                            <div class="col-sm-3">
                                <input type="text" name="daya_listrik" id="daya_listrik"
                                    class="form-control format-number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-4">
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                            </div>
                            <label class="col-sm-2 col-form-label">No. Sertif</label>
                            <div class="col-sm-3">
                                <input type="text" name="no_sertifikat" id="no_sertifikat" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalUploadLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalUploadLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formUpload" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="primary_upload" name="primary_upload">
                    <div class="modal-body p-4">
                        <div id="dropzoneFoto" class="dropzone-wrapper">
                            <input type="file" name="foto" id="foto" accept="image/*" capture="environment" hidden>
                            <div id="previewFoto" class="dropzone-content">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted font-weight-bold mb-1">Upload Foto Kavling</p>
                                <p class="text-muted small mb-0">Klik, ambil foto, atau seret foto ke sini</p>
                            </div>
                        </div>
                        <div class="dropzone-error" id="dropzoneError-foto"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ms-1" id="submitUpload">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark font-weight-bold" id="modalImportLabel">Import Excel Kavling</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kavling.importExcel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle mr-1"></i>
                            Gunakan file hasil <strong>Cetak Excel</strong> yang sudah diedit. Pastikan header kolom tidak diubah.
                        </div>
                        <div class="form-group">
                            <label for="file">File Excel</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-upload mr-1"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .dropzone-wrapper {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .dropzone-wrapper:hover {
            border-color: #00a651;
            background-color: #f3f0ff;
        }
        .dropzone-wrapper.dragover {
            border-color: #00a651;
            background-color: #f3f0ff;
        }
        .dropzone-content {
            text-align: center;
            pointer-events: none;
        }
        .dropzone-content img {
            max-width: 100%;
            max-height: 350px;
            object-fit: contain;
        }
        .dropzone-wrapper.is-invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        .dropzone-wrapper.is-invalid:hover {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        .dropzone-error .invalid-feedback {
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select-lokasi').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Lokasi",
            });
        });


        function resetDropzone() {
            $('#previewFoto').html(`
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted font-weight-bold mb-1">Upload Foto Kavling</p>
                <p class="text-muted small mb-0">Klik, ambil foto, atau seret foto ke sini</p>
            `);
        }

        function renderPreviewWithRemoveBtn(html) {
            $('#previewFoto').html(`
                ${html}
                <button type="button" class="btn btn-sm btn-danger btn-remove-foto"
                    style="position: absolute; top: 8px; right: 8px; border-radius: 50%; width: 28px; height: 28px; padding: 0; line-height: 1; z-index: 10; pointer-events: auto;">
                    <i class="fas fa-times"></i>
                </button>
            `);
        }

        $(document).on('click', '.btn-remove-foto', function(e) {
            e.stopPropagation();
            $('#foto').val('');
            resetDropzone();
        });

        $('#foto').on('change', function() {
            const file = this.files[0];
            const previewDiv = $('#previewFoto');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    renderPreviewWithRemoveBtn(`<img src="${e.target.result}" alt="Preview">`);
                };
                reader.readAsDataURL(file);
            } else {
                resetDropzone();
            }
        });

        const dropzoneEl = document.getElementById('dropzoneFoto');
        const fileInput = document.getElementById('foto');

        if (dropzoneEl) {
            dropzoneEl.addEventListener('click', function() {
                fileInput.click();
            });

            dropzoneEl.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            dropzoneEl.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            dropzoneEl.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length) {
                    fileInput.files = files;
                    $(fileInput).trigger('change');
                }
            });
        }

        document.querySelectorAll('.format-decimal').forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = input.value;
                value = value.replace(/[^0-9.]/g, '');
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts[1];
                }
                if (parts.length === 2) {
                    parts[1] = parts[1].slice(0, 2);
                    value = parts[0] + '.' + parts[1];
                }
                input.value = value;
            });
        });

        $(function() {
            var permissions = @json($permissions);
            var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: {
                    url: "{{ route('kavling.index') }}",
                    data: function(d) {
                        d.id_lokasi = $('#id_lokasi').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: null,
                        name: 'lokasi_kavling',
                        orderable: false,
                        searchable: true,
                        render: function(data, type, row) {
                            let lokasi = row.id_lokasi || '-';
                            let kode = row.kode_kavling || '';
                            if (type === 'filter' || type === 'sort') {
                                return lokasi + ' ' + kode;
                            }
                            return lokasi + '<br>' + kode ;
                        }
                    },
                    {
                        data: 'panjang',
                        name: 'panjang',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lebar',
                        name: 'lebar',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'luas',
                        name: 'luas',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'rincian_harga',
                        name: 'rincian_harga',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: showActionColumn,
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, ]
            });
            $('#id_lokasi').on('change', function() {
                let idLokasi = $(this).val();

                let pdfRoute = "{{ route('kavling.cetakPdf', ['id_lokasi' => '__ID__']) }}";
                let excelRoute = "{{ route('kavling.cetakExcel', ['id_lokasi' => '__ID__']) }}";

                $('#btnPdf').attr('href', pdfRoute.replace('__ID__', idLokasi));
                $('#btnExcel').attr('href', excelRoute.replace('__ID__', idLokasi));

                table.ajax.reload();
            }).trigger('change');
        });


        $(document).on('click', '.foto-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    let data = response.data;
                    $('#modalUploadLabel').text('Foto Kavling');
                    $('#primary_upload').val(data.id);
                    let foto = response.data.foto;
                    if (foto) {
                        let imageUrl = '/assets/foto_kavling/' + foto;
                        renderPreviewWithRemoveBtn(`<img src="${imageUrl}" alt="Foto Kavling">`);
                    } else {
                        resetDropzone();
                    }

                    $('#modalUpload').modal('show');
                }
            });
        });

        let deletedBiayaNames = [];
        let biayaRowIndex = 0;

        function escapeHtml(value) {
            return $('<div>').text(value || '').html();
        }

        function renderBiayaItems(items) {
            const formatNumber = (number) => {
                let num = parseFloat(number);
                return isNaN(num) ? '' : num.toLocaleString('id-ID');
            };

            let html = '';
            let total = 0;
            biayaRowIndex = 0;
            (items || []).forEach(function(item, idx) {
                idx = biayaRowIndex++;
                let nilai = item.nilai || 0;
                total += nilai;
                let nama = escapeHtml(item.nama || '');
                html += `
                    <div class="biaya-row form-row mb-1">
                        <input type="hidden" name="rincian_biaya[${idx}][original_nama]" value="${nama}">
                        <div class="col-sm-4">
                            <input type="text" name="rincian_biaya[${idx}][nama]"
                                class="form-control form-control-sm biaya-nama"
                                value="${nama}" placeholder="Nama biaya">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" name="rincian_biaya[${idx}][nilai]"
                                    class="form-control format-number biaya-nilai"
                                    value="${formatNumber(nilai)}" placeholder="0">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="custom-control custom-checkbox mt-1">
                                <input type="checkbox" name="rincian_biaya[${idx}][update_semua]"
                                    value="1" class="custom-control-input" id="update-semua-${idx}">
                                <label class="custom-control-label small" for="update-semua-${idx}">Update semua</label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger btn-sm btn-block hapus-biaya">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            $('#dynamic-biaya-container').html(html);
            hitungTotalBiaya();
        }

        function hitungTotalBiaya() {
            let total = 0;
            $('.biaya-nilai').each(function() {
                let val = parseInt($(this).val().replace(/\./g, '')) || 0;
                total += val;
            });
            const formatNumber = (number) => {
                let num = parseFloat(number);
                return isNaN(num) ? '' : num.toLocaleString('id-ID');
            };
            $('#total_harga_display').val(formatNumber(total));
        }

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    let data = response.data;

                    const formatNumber = (number) => {
                        let num = parseFloat(number);
                        return isNaN(num) ? '' : num.toLocaleString('id-ID');
                    };

                    $('#modalFormLabel').text('Edit Kavling');
                    $('#primary_id').val(data.id);
                    $('#nama_kavling').val(data.lokasi.nama_kavling);
                    $('#kode_kavling').val(data.kode_kavling);
                    $('#panjang_kanan').val(data.panjang_kanan);
                    $('#panjang_kiri').val(data.panjang_kiri);
                    $('#lebar_depan').val(data.lebar_depan);
                    $('#lebar_belakang').val(data.lebar_belakang);
                    $('#luas_tanah').val(data.luas_tanah);
                    $('#luas_bangunan').val(data.luas_bangunan);
                    $('#hrg_meter').val(formatNumber(data.hrg_meter));
                    $('#tipe_bangunan').val(formatNumber(data.tipe_bangunan));
                    $('#daya_listrik').val(formatNumber(data.daya_listrik));

                    deletedBiayaNames = [];
                    $('#deleted-biaya-container').remove();
                    renderBiayaItems(data.rincian_biaya || []);

                    $('#keterangan').val(data.keterangan);
                    $('#no_sertifikat').val(data.no_sertifikat);

                    $('#modalForm').modal('show');
                }
            });
        });

        $(document).on('click', '#tambahBiayaBtn', function() {
            let idx = biayaRowIndex++;
            let html = `
                <div class="biaya-row form-row mb-1">
                    <input type="hidden" name="rincian_biaya[${idx}][original_nama]" value="">
                    <div class="col-sm-4">
                        <input type="text" name="rincian_biaya[${idx}][nama]"
                            class="form-control form-control-sm biaya-nama"
                            placeholder="Nama biaya">
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" name="rincian_biaya[${idx}][nilai]"
                                class="form-control format-number biaya-nilai"
                            placeholder="0">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="custom-control custom-checkbox mt-1">
                            <input type="checkbox" name="rincian_biaya[${idx}][update_semua]"
                                value="1" class="custom-control-input" id="update-semua-${idx}">
                            <label class="custom-control-label small" for="update-semua-${idx}">Update semua</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-danger btn-sm btn-block hapus-biaya">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#dynamic-biaya-container').append(html);
        });

        $(document).on('click', '.hapus-biaya', function() {
            let row = $(this).closest('.biaya-row');
            let originalName = row.find('input[name$="[original_nama]"]').val();
            let currentName = row.find('.biaya-nama').val();
            let deletedName = originalName || currentName;

            if (deletedName && !deletedBiayaNames.includes(deletedName)) {
                deletedBiayaNames.push(deletedName);
            }

            row.remove();
            hitungTotalBiaya();
        });

        $(document).on('input', '.biaya-nilai', function() {
            hitungTotalBiaya();
        });



        $(document).on('input', '.biaya-input', function() {
            let total = 0;
            $('.biaya-input').each(function() {
                let val = parseInt($(this).val().replace(/\./g, '')) || 0;
                total += val;
            });
            $('#total_harga_display').val(formatNumber(total));
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#modalUpload').on('hidden.bs.modal', function() {
            $('#formUpload')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.dropzone-error').html('');
            resetDropzone();

            let submitBtn = $('#submitUpload');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            resetDropzone();
        });


        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = id ? '{{ route('kavling.update', ['kavling' => ':id']) }}'.replace(':id',
                    id) :
                '{{ route('kavling.store') }}';
            let method = id ? 'PUT' : 'POST';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);
            deletedBiayaNames.forEach(function(name) {
                formData.append('deleted_biaya[]', name);
            });
            formData.append('_method', method);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalForm').modal('hide');
                    audio.play();
                    let msg = id ? "Kavling berhasil diupdate!" :
                        "Kavling berhasil ditambahkan!";
                    toastr.success(msg, "BERHASIL", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                    $('.data-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        audio.play();
                        toastr.error("Ada inputan yang salah!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            let input = $('#' + key);
                            input.addClass('is-invalid');
                            input.parent().find('.invalid-feedback').remove();
                            input.parent().append(
                                '<span class="invalid-feedback" role="alert"><strong>' +
                                val[0] + '</strong></span>'
                            );
                        });

                        spinner.addClass('d-none');
                        btnText.text('Simpan');
                        submitBtn.prop('disabled', false);
                    }
                }
            });
        });

        $('#formUpload').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitUpload');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_upload').val();
            let url = '{{ route('kavling.foto-update', ['id' => ':id']) }}'.replace(':id', id);
            let method = 'PUT';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);
            formData.append('_method', method);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalUpload').modal('hide');
                    audio.play();
                    let msg = "Foto Kavling berhasil diupload!";
                    toastr.success(msg, "BERHASIL", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                    $('.data-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        audio.play();
                        toastr.error("Ada inputan yang salah!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            let input = $('#' + key);
                            if (input.is('[type="file"]') && input.parent().hasClass('dropzone-wrapper')) {
                                input.parent().addClass('is-invalid');
                                $('#dropzoneError-' + key).html(
                                    '<span class="invalid-feedback" role="alert"><strong>' +
                                    val[0] + '</strong></span>'
                                );
                            } else {
                                input.addClass('is-invalid');
                                input.parent().find('.invalid-feedback').remove();
                                input.parent().append(
                                    '<span class="invalid-feedback" role="alert"><strong>' +
                                    val[0] + '</strong></span>'
                                );
                            }
                        });

                        spinner.addClass('d-none');
                        btnText.text('Simpan');
                        submitBtn.prop('disabled', false);
                    }
                }
            });
        });
    </script>
@endpush
