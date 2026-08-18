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
                                    <h3 class="font-weight-bold text-lg">Upload Template Dokumen</h3>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#modalVariabel">
                                            <i class="fas fa-list"></i> Lihat Variabel
                                        </button>
                                        @if ($permissions['tambah'])
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalForm">
                                                <i class="fas fa-plus"></i> Tambah Template
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered w-100 table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama</th>
                                            <th>Kode</th>
                                            <th>Engine</th>
                                            <th>Status</th>
                                            <th>Dibuat</th>
                                            <th class="text-center" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false"
         aria-labelledby="modalFormLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Form Template</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Nama Template <span style="color: red;">*</span></label>
                            <div class="col-sm-8">
                                <input name="nama" id="nama" class="form-control" type="text" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Kode Template <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <input name="kode" id="kode" class="form-control" type="text" required
                                       placeholder="Contoh: form_subsidi">
                                <small class="text-muted">Kode unik, gunakan snake_case</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Engine <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select name="engine" id="engine" class="form-control select-engine" required>
                                    <option value=""></option>
                                    <option value="docx">DOCX (Word)</option>
                                    <option value="pdf">PDF</option>
                                    <option value="html">HTML</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="fileUploadRow">
                            <label class="control-label col-sm-3">File Template</label>
                            <div class="col-sm-8">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file_template" id="file_template"
                                           accept=".docx,.pdf">
                                    <label class="custom-file-label" for="file_template">Pilih file...</label>
                                </div>
                                <small class="text-muted">Format: DOCX / PDF, maks 5MB</small>
                                <div id="currentFileInfo" class="mt-2" style="display: none;"></div>
                                <input type="hidden" id="existing_file_path" name="existing_file_path">
                            </div>
                        </div>
                        <div class="form-group row" id="kontenRow" style="display: none;">
                            <label class="control-label col-sm-3">Konten HTML</label>
                            <div class="col-sm-8">
                                <textarea name="konten" id="konten" class="form-control" rows="8"
                                          placeholder="Gunakan {$nama_variabel} untuk placeholder"></textarea>
                                <small class="text-muted">Gunakan format <code>{$nama_variabel}</code> untuk data dinamis</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Status</label>
                            <div class="col-sm-4">
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVariabel" tabindex="-1" role="dialog" data-focus="false"
         aria-labelledby="modalVariabelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white font-weight-bold" id="modalVariabelLabel">
                        <i class="fas fa-code mr-2"></i> Daftar Variabel Tersedia
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Contoh penggunaan:</strong>
                        <code>Nama Nasabah: {$nama_lengkap}</code> akan diganti dengan data nasabah saat cetak.
                    </div>
                    <p class="text-muted">Klik variabel untuk copy ke clipboard. Gunakan di template sesuai engine:</p>
                    <div class="row">
                        @foreach ($contextKeys as $group => $keys)
                            <div class="col-md-12 mb-3">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <strong>{{ $group }}</strong>
                                    </div>
                                    <div class="card-body py-2">
                                        @foreach ($keys as $key)
                                            @php $varName = '{$' . $key . '}'; @endphp
                                            <code class="d-inline-block mr-2 mb-1" style="cursor:pointer;"
                                                  onclick="copyToClipboard('{{ $varName }}')"
                                                  title="Klik untuk copy">{{ $varName }}</code>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();

            $('.select-engine').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Engine",
            });
        });

        $('#engine').on('change', function() {
            var val = $(this).val();
            if (val === 'html') {
                $('#fileUploadRow').hide();
                $('#kontenRow').show();
            } else {
                $('#fileUploadRow').show();
                $('#kontenRow').hide();
            }
        });

        var permissions = @json($permissions);
        var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

        var table = $('.data-table').DataTable({
            processing: false,
            serverSide: false,
            ordering: false,
            responsive: true,
            ajax: "{{ route('upload-template.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nama', name: 'nama', orderable: false, searchable: true },
                { data: 'kode', name: 'kode', orderable: false, searchable: true },
                { data: 'engine', name: 'engine', orderable: false, searchable: false, className: 'text-center' },
                { data: 'is_active', name: 'is_active', orderable: false, searchable: false, className: 'text-center' },
                { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, visible: showActionColumn, className: 'text-center' }
            ],
            columnDefs: [{
                targets: 0,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }]
        });

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.success) {
                    var data = response.data;
                    $('#primary_id').val(data.id);
                    $('#nama').val(data.nama);
                    $('#kode').val(data.kode);
                    $('#engine').val(data.engine).trigger('change');
                    $('#deskripsi').val(data.deskripsi || '');
                    $('#is_active').val(data.is_active ? '1' : '0');
                    $('#konten').val(data.konten || '');

                    if (data.file_path) {
                        $('.custom-file-label').text(data.file_path);
                        $('#currentFileInfo').html(
                            '<small class="text-success"><i class="fas fa-check-circle"></i> File tersimpan: <strong>' +
                            data.file_path + '</strong></small>'
                        ).show();
                        $('#existing_file_path').val(data.file_path);
                    }

                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#primary_id').val('');
            $('#engine').val('').trigger('change');
            $('#fileUploadRow').show();
            $('#kontenRow').hide();
            $('.custom-file-label').text('Pilih file...');
            $('#currentFileInfo').hide().empty();
            $('#existing_file_path').val('');

            let submitBtn = $('#submitBtn');
            submitBtn.find('.spinner-border').addClass('d-none');
            submitBtn.find('.button-text').text('Simpan');
            submitBtn.prop('disabled', false);
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
            let url = id ? '{{ route('upload-template.update', ['upload_template' => ':id']) }}'.replace(':id', id) :
                '{{ route('upload-template.store') }}';
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
                    let msg = id ? 'Template berhasil diupdate!' : 'Template berhasil ditambahkan!';
                    toastr.success(msg, 'BERHASIL', {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: 'toast-bottom-right',
                    });
                    $('.data-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        audio.play();
                        toastr.error('Ada inputan yang salah!', 'GAGAL!', {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: 'toast-bottom-right',
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
                    }

                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Template ini akan dihapus secara permanen!',
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

                        btnText.innerHTML = `<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Menghapus...`;
                        confirmBtn.disabled = true;

                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function() {
                                audio.play();
                                toastr.success('Template telah dihapus!', 'BERHASIL', {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: 'toast-bottom-right'
                                });
                                $('.data-table').DataTable().ajax.reload(null, false);
                                Swal.close();
                            },
                            error: function() {
                                audio.play();
                                toastr.error('Gagal menghapus template.', 'GAGAL!', {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: 'toast-bottom-right'
                                });
                                btnText.innerHTML = 'Ya, Hapus';
                                confirmBtn.disabled = false;
                            }
                        });
                    });
                }
            });
        });

        function copyToClipboard(text) {
            var temp = $('<input>');
            $('body').append(temp);
            temp.val(text).select();
            document.execCommand('copy');
            temp.remove();
            toastr.success('Variabel ' + text + ' berhasil dicopy!', 'COPY', {
                progressBar: true,
                timeOut: 1500,
                positionClass: 'toast-bottom-right'
            });
        }
    </script>
@endpush
