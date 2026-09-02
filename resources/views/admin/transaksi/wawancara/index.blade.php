@extends('admin.layout_admin')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-3">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Data Wawancara</h3>
                                    <div class="d-flex align-items-center" style="gap: 3px">
                                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#modalForm"><i class="fas fa-plus" id="btnTambah"></i>
                                            Tambah Data Wawancara</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered small w-100 table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Customer</th>
                                            <th>Lokasi Rumah</th>
                                            <th>Tgl Wawancara</th>
                                            <th>Bank KPR</th>
                                            <th>Catatan</th>
                                            <th class="text-center" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false" data-modal-type="">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Form Wawancara</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="modal-body">

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Hari</label>
                            <div class="col-sm-4">
                                <input type="text" name="hari_wawancara" id="hari_wawancara" class="form-control"
                                    readonly value="{{ $hari }}">
                            </div>
                            <label class="col-sm-2 col-form-label">Tanggal</label>
                            <div class="col-sm-4">
                                <input type="date" name="tgl_wawancara" id="tgl_wawancara" class="form-control"
                                    value="{{ now()->toDateString() }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Customer</label>
                            <div class="col-sm-8">
                                <select name="id_customer" id="id_customer" class="form-control select-customer">
                                    <option value=""></option>
                                    @foreach ($customerList as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama_lengkap }} ({{ $m->kode_customer }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">NIK</label>
                            <div class="col-sm-8">
                                <input type="text" name="nik" id="nik" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Alamat KTP</label>
                            <div class="col-sm-8">
                                <textarea id="alamat_ktp" name="alamat_ktp" class="form-control" disabled rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Lokasi Rumah</label>
                            <div class="col-sm-4">
                                <input type="text" name="lokasi_rumah" id="lokasi_rumah" class="form-control" disabled>
                            </div>
                            <label class="col-sm-2 col-form-label">Tipe Bangunan</label>
                            <div class="col-sm-4">
                                <input type="text" name="tipe_bangunan" id="tipe_bangunan" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Luas Tanah</label>
                            <div class="col-sm-4">
                                <input type="text" name="luas_tanah" id="luas_tanah" class="form-control" disabled>
                            </div>
                            <label class="col-sm-2 col-form-label">Luas Bangunan</label>
                            <div class="col-sm-4">
                                <input type="text" name="luas_bangunan" id="luas_bangunan" class="form-control"
                                    disabled>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Marketing</label>
                            <div class="col-sm-8">
                                <input type="text" id="nama_marketing" name="nama_marketing" class="form-control"
                                    disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Bank</label>
                            <div class="col-sm-8">
                                <select name="id_bank_kpr" id="id_bank_kpr" class="form-control select-bank-kpr">
                                    <option value=""></option>
                                    @foreach ($bankKPRList as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Catatan Wawancara</label>
                            <div class="col-sm-8">
                                <textarea id="catatan_wawancara" name="catatan_wawancara" class="form-control summernote"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary " id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAcc" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalAccLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"
        data-modal-type="">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalAccLabel">Form SP3K</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formAcc" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id_wawancara" name="id_wawancara">
                    <div class="modal-body">

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Customer</label>
                            <div class="col-sm-10">
                                <select name="id_customer_acc" id="id_customer_acc" class="form-control select-customer"
                                    disabled>
                                    <option value=""></option>
                                    @foreach ($customerList as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama_lengkap }}
                                            ({{ $m->kode_customer }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Acc Plafon</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" name="acc_plafon" id="acc_plafon"
                                        class="form-control format-number">
                                </div>
                            </div>
                            <label class="col-sm-2 col-form-label">Tenor</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" name="tenor" id="tenor"
                                        class="form-control format-number">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Tahun</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Lampiran</label>
                            <div class="col-sm-8">
                                <div id="dropzoneLampiran" class="dropzone-wrapper">
                                    <input type="file" name="lampiran" id="lampiran" accept=".jpg, .jpeg, .png, .pdf" hidden>
                                    <div id="previewLampiran" class="dropzone-content">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted font-weight-bold mb-1">Upload Lampiran</p>
                                        <p class="text-muted small mb-0">Klik atau seret file ke sini</p>
                                    </div>
                                </div>
                                <div class="dropzone-error" id="dropzoneError-lampiran"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal SP3K</label>
                            <div class="col-sm-4">
                                <input type="date" name="tgl_terbit_sp3k" id="tgl_terbit_sp3k" class="form-control"
                                    value="{{ now()->toDateString() }}">
                            </div>
                            <label class="col-sm-2 col-form-label">No. SP3K</label>
                            <div class="col-sm-4">
                                <input type="text" name="no_sp3k" id="no_sp3k" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Bank</label>
                            <div class="col-sm-4">
                                <select name="id_bank_kpr_acc" id="id_bank_kpr_acc" class="form-control select-bank-kpr"
                                    disabled>
                                    <option value=""></option>
                                    @foreach ($bankKPRList as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Notaris</label>
                            <div class="col-sm-4">
                                <select name="id_notaris" id="id_notaris" class="form-control select-notaris">
                                    <option value=""></option>
                                    @foreach ($notarisList as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama_notaris }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-sm-2 col-form-label">Catatan ACC</label>
                            <div class="col-sm-10">
                                <textarea id="catatan_wawancara" name="catatan_wawancara" class="form-control summernote"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary " id="accBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
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
            min-height: 200px;
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
            max-height: 200px;
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
            $('.select-customer').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Customer",
            });
            $('.select-bank-kpr').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Bank KPR",
            });
            $('.select-notaris').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Notaris",
            });
        });

        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200,
                placeholder: 'Tulis catatan di sini...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });

        $(document).on('change', '#id_customer', function() {
            let id = $(this).val()

            if (!id) {
                $('#nik, #alamat_ktp, #lokasi_rumah, #tipe_bangunan, #luas_tanah, #luas_bangunan, #nama_marketing')
                    .val('')
                return
            }

            const detailCustomerUrl = "{{ route('wawancara.detail-customer', ':id') }}"

            let url = detailCustomerUrl.replace(':id', id)

            $.get(url, function(res) {
                $('#nik').val(res.nik)
                $('#alamat_ktp').val(res.alamat_ktp)
                $('#lokasi_rumah').val(res.lokasi_rumah)
                $('#tipe_bangunan').val(res.tipe_bangunan)
                $('#luas_tanah').val(res.luas_tanah)
                $('#luas_bangunan').val(res.luas_bangunan)
                $('#nama_marketing').val(res.nama_marketing)
            })
        })


        $(function() {
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: "{{ route('wawancara.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id_customer',
                        name: 'id_customer',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'lokasi_rumah',
                        name: 'lokasi_rumah',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tgl_wawancara',
                        name: 'tgl_wawancara',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id_bank_kpr',
                        name: 'id_bank_kpr',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'catatan_wawancara',
                        name: 'catatan_wawancara',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, ]
            });
        });

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#primary_id').val(response.data.id);
                    $('#tgl_wawancara').val(response.data.tgl_wawancara);
                    $('#hari_wawancara').val(response.data.hari_wawancara);
                    $('#id_customer').val(response.data.id_customer).trigger('change').prop('disabled',
                        true);
                    $('#id_bank_kpr').val(response.data.id_bank_kpr).trigger('change');
                    $('#catatan_wawancara').summernote('code', response.data.catatan_wawancara);

                    $('#modalForm').modal('show');
                }
            });
        });

        $(document).on('click', '.acc-bank-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#id_wawancara').val(response.data.id);
                    $('#id_customer_acc').val(response.data.id_customer).trigger('change');
                    $('#id_bank_kpr_acc').val(response.data.id_bank_kpr).trigger('change');

                    $('#modalAcc').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $('#primary_id').val('');
            $('#id_customer').val('').trigger('change').prop('disabled', false);
            $('#id_bank_kpr').val('').trigger('change');
            $('#catatan_wawancara').summernote('reset');

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        function resetDropzoneAcc() {
            $('#previewLampiran').html(`
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted font-weight-bold mb-1">Upload Lampiran</p>
                <p class="text-muted small mb-0">Klik atau seret file ke sini</p>
            `);
        }

        function renderPreviewWithRemoveBtnAcc(html) {
            $('#previewLampiran').html(`
                ${html}
                <button type="button" class="btn btn-sm btn-danger btn-remove-lampiran"
                    style="position: absolute; top: 8px; right: 8px; border-radius: 50%; width: 28px; height: 28px; padding: 0; line-height: 1; z-index: 10; pointer-events: auto;">
                    <i class="fas fa-times"></i>
                </button>
            `);
        }

        $(document).on('click', '.btn-remove-lampiran', function(e) {
            e.stopPropagation();
            $('#lampiran').val('');
            resetDropzoneAcc();
        });

        $('#lampiran').on('change', function() {
            const file = this.files[0];
            const previewDiv = $('#previewLampiran');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    renderPreviewWithRemoveBtnAcc(`<img src="${e.target.result}" alt="Preview">`);
                };
                reader.readAsDataURL(file);
            } else if (file) {
                renderPreviewWithRemoveBtnAcc(`
                    <div class="text-muted">
                        <i class="fas fa-file fa-3x mb-2"></i>
                        <p class="font-weight-bold mb-0">${file.name}</p>
                    </div>
                `);
            } else {
                resetDropzoneAcc();
            }
        });

        const dropzoneAccEl = document.getElementById('dropzoneLampiran');
        const fileInputAcc = document.getElementById('lampiran');

        if (dropzoneAccEl) {
            dropzoneAccEl.addEventListener('click', function() {
                fileInputAcc.click();
            });

            dropzoneAccEl.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            dropzoneAccEl.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            dropzoneAccEl.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length) {
                    fileInputAcc.files = files;
                    $(fileInputAcc).trigger('change');
                }
            });
        }

        $('#modalAcc').on('hidden.bs.modal', function() {
            $('#formAcc')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.dropzone-error').html('');

            $('#id_wawancara').val('');
            $('#catatan_wawancara').summernote('reset');

            let submitBtn = $('#accBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            resetDropzoneAcc();
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
            let url = id ? '{{ route('wawancara.update', ['wawancara' => ':id']) }}'.replace(':id', id) :
                '{{ route('wawancara.store') }}';
            let method = id ? 'PUT' : 'POST';

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
                    $('#modalForm').modal('hide');
                    audio.play();
                    let msg = id ? "Wawancara berhasil diupdate!" : "Wawancara berhasil ditambahkan!";
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

        $('#formAcc').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#accBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#id_wawancara').val();
            let url = '{{ route('wawancara.sp3k', ['id_wawancara' => ':id']) }}'.replace(':id', id);
            let method = 'POST';

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
                    $('#modalAcc').modal('hide');
                    audio.play();
                    let msg = "SP3K Berhasil disimpan!";
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

        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="swal-btn-text">Ya, Hapus</span>',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: false,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-secondary'
                },
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const confirmBtn = Swal.getConfirmButton();
                        const btnText = confirmBtn.querySelector('.swal-btn-text');

                        btnText.innerHTML =
                            `<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Menghapus...`;
                        confirmBtn.disabled = true;

                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function() {
                                audio.play();
                                toastr.success("Data telah dihapus!", "BERHASIL", {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: "toast-bottom-right"
                                });

                                $('.data-table').DataTable().ajax.reload(null,
                                    false);
                                Swal.close();
                            },
                            error: function() {
                                audio.play();
                                toastr.error("Gagal menghapus data.", "GAGAL!", {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: "toast-bottom-right"
                                });

                                btnText.innerHTML = `Ya, Hapus`;
                                confirmBtn.disabled = false;
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush
