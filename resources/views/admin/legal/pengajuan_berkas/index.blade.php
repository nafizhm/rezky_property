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
                                    <h3 class="font-weight-bold text-lg">Data Kelengkapan Berkas</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered small table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="4%">No</th>
                                            <th width="25%">Nama Customer</th>
                                            <th width="7%">IPH</th>
                                            <th width="7%">SHGB</th>
                                            <th width="7%">SSP</th>
                                            <th width="7%">BPHTB</th>
                                            <th width="9%">SIKUMBANG</th>
                                            <th width="10%">DAFTAR SIKASEP</th>
                                            <th width="10%">FOTO SIKASEP</th>
                                            <th width="8%">TRILOGI</th>
                                            <th class="text-center" width="10%">Action</th>
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
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md" role="document">
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
                            <label class="col-sm-4 col-form-label">Customer</label>
                            <div class="col-sm-8">
                                <input name="nama_lengkap" type="text" class="form-control" id="nama_lengkap" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="IPH" class="col-sm-4 col-form-label">IPH</label>
                            <div class="col-sm-5">
                                <select name="IPH" id="IPH" class="form-select select-iph">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="SHGB" class="col-sm-4 col-form-label">SHGB</label>
                            <div class="col-sm-5">
                                <select name="SHGB" id="SHGB" class="form-select select-shgb">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="SSP" class="col-sm-4 col-form-label">SSP</label>
                            <div class="col-sm-5">
                                <select name="SSP" id="SSP" class="form-select select-ssp">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="BPHTB" class="col-sm-4 col-form-label">BPHTB</label>
                            <div class="col-sm-5">
                                <select name="BPHTB" id="BPHTB" class="form-select select-bphtb">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="SIKUMBANG" class="col-sm-4 col-form-label">SIKUMBANG</label>
                            <div class="col-sm-5">
                                <select name="SIKUMBANG" id="SIKUMBANG" class="form-select select-sikumbang">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="DAFTAR_SIKASEP" class="col-sm-4 col-form-label">DAFTAR SIKASEP</label>
                            <div class="col-sm-5">
                                <select name="DAFTAR_SIKASEP" id="DAFTAR_SIKASEP"
                                    class="form-select select-daftar-sikasep">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="FOTO_SIKASEP" class="col-sm-4 col-form-label">FOTO SIKASEP</label>
                            <div class="col-sm-5">
                                <select name="FOTO_SIKASEP" id="FOTO_SIKASEP" class="form-select select-foto-sikasep">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="TRILOGI" class="col-sm-4 col-form-label">TRILOGI</label>
                            <div class="col-sm-5">
                                <select name="TRILOGI" id="TRILOGI" class="form-select select-trilogi">
                                    <option value=""></option>
                                    <option value="0">Belum Ada</option>
                                    <option value="1">Ada</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Percakapan WA</label>
                            <div class="col-sm-5">
                                <input type="file" class="mb-2" id="percakapan_wa" name="percakapan_wa"
                                    accept=".jpg, .jpeg, .png">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewPercakapanWa"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada foto</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Catatan Berkas</label>
                            <div class="col-md-8">
                                <textarea name="catatan_kekurangan" class="form-control" id="catatan_kekurangan" rows="3"></textarea>
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
@endsection
@push('scripts')
    <script>
        previewFile('percakapan_wa', 'previewPercakapanWa');

        $(document).ready(function() {
            $('.select-iph').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status IPH",
                minimumResultsForSearch: Infinity
            });
            $('.select-shgb').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status SHGB",
                minimumResultsForSearch: Infinity
            });
            $('.select-ssp').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status SSP",
                minimumResultsForSearch: Infinity
            });
            $('.select-bphtb').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status BPHTB",
                minimumResultsForSearch: Infinity
            });
            $('.select-sikumbang').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status SIKUMBANG",
                minimumResultsForSearch: Infinity
            });
            $('.select-daftar-sikasep').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status DAFTAR SIKASEP",
                minimumResultsForSearch: Infinity
            });
            $('.select-foto-sikasep').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status FOTO SIKASEP",
                minimumResultsForSearch: Infinity
            });
            $('.select-trilogi').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status TRILOGI",
                minimumResultsForSearch: Infinity
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
                ajax: "{{ route('pengajuan-berkas.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_customer',
                        name: 'nama_customer',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'IPH',
                        name: 'IPH',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'SHGB',
                        name: 'SHGB',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'SSP',
                        name: 'SSP',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'BPHTB',
                        name: 'BPHTB',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'SIKUMBANG',
                        name: 'SIKUMBANG',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'DAFTAR_SIKASEP',
                        name: 'DAFTAR_SIKASEP',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'FOTO_SIKASEP',
                        name: 'FOTO_SIKASEP',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'TRILOGI',
                        name: 'TRILOGI',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        visible: showActionColumn
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

        // Tombol edit
        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#modalFormLabel').text('Edit Pengajuan Berkas');

                    $('#primary_id').val(response.data.id);
                    $('#nama_lengkap').val(response.data.customer.nama_lengkap);
                    $('#IPH').val(response.data.IPH).trigger('change');
                    $('#SHGB').val(response.data.SHGB).trigger('change');
                    $('#SSP').val(response.data.SSP).trigger('change');
                    $('#BPHTB').val(response.data.BPHTB).trigger('change');
                    $('#SIKUMBANG').val(response.data.SIKUMBANG).trigger('change');
                    $('#DAFTAR_SIKASEP').val(response.data.DAFTAR_SIKASEP).trigger('change');
                    $('#FOTO_SIKASEP').val(response.data.FOTO_SIKASEP).trigger('change');
                    $('#TRILOGI').val(response.data.TRILOGI).trigger('change');
                    $('#catatan_kekurangan').val(response.data.catatan_kekurangan);
                    setPreview(response.data.percakapan_wa, 'assets/legal/pengajuan_berkas/percakapan_wa',
                        'previewPercakapanWa');

                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('.form-select').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            $('#previewPercakapanWa').html('<span style="color: #6c757d;">Tidak ada foto</span>');
        });

        // Simpan / Update data
        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = id ? '{{ route('pengajuan-berkas.update', ['pengajuan_berka' => ':id']) }}'.replace(':id',
                    id) :
                '{{ route('pengajuan-berkas.store') }}';
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
                success: function() {
                    $('#modalForm').modal('hide');
                    audio.play();
                    toastr.success("Berkas Pengajuan telah disimpan!", "BERHASIL", {
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


        // Hapus data
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
