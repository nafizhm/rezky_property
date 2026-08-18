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
                        @if ($auth->id_role == 1)
                            <div class="card">
                                <div class="card-header p-3">
                                    <div class="d-flex align-content-center justify-content-between">
                                        <h3 class="font-weight-bold text-lg">Data Pengguna</h3>
                                        <div class="d-flex align-items-center">
                                            @if (isset($permissions['tambah']) && $permissions['tambah'] == 1)
                                                <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#modalForm"><i class="fas fa-plus"></i>
                                                    Tambah Pengguna</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped data-table">
                                        <thead>
                                            <tr>
                                                <th width="50px">No</th>
                                                <th>Nama Lengkap</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th width="100px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        @else
                            <div class="card">
                                <div class="card-header p-3">
                                    <div class="d-flex align-content-center justify-content-between">
                                        <h3 class="font-weight-bold text-lg">Data Pengguna</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form id="formUser">
                                        @csrf
                                        <input type="hidden" id="id_user" name="id_user" value="{{ $auth->id }}">
                                        <div class="form-group row mb-3">
                                            <label for="username" class="col-sm-3 col-form-label">Username</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="username" name="username"
                                                    value="{{ $auth->username }}">
                                            </div>
                                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-3">
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>
                                        </div>

                                        <!-- Email Field -->
                                        <div class="form-group row mb-3">
                                            <label for="nama_lengkap" class="col-sm-3 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="surname" name="surname"
                                                    value="{{ $auth->surname }}">
                                            </div>
                                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-3">
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ $auth->email }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                                                <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                                    aria-hidden="true"></span>
                                                <span class="button-text">Simpan</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        @endif
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

    @if ($auth->id_role == 1)
        <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-indigo">
                        <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel"></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formData">
                        @csrf
                        <input type="hidden" id="primary_id" name="primary_id">
                        <div class="modal-body">
                            <!-- Nama Lengkap Field -->
                            <div class="form-group row mb-3">
                                <label for="username" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="username" name="username">
                                </div>
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-3">
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="form-group row mb-3">
                                <label for="nama_lengkap" class="col-sm-3 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="surname" name="surname">
                                </div>
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-3">
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>

                            <!-- Status Field -->
                            <div class="form-group row mb-3">
                                <label for="status" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-4">
                                    <select name="status" id="status" class="form-select select-status">
                                        <option value=""></option>
                                        <option value="AKTIF">AKTIF</option>
                                        <option value="BLOKIR">BLOKIR</option>
                                    </select>
                                </div>
                                <label for="role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-3">
                                    <select name="role" id="role" class="form-select select-role">
                                        <option value=""></option>
                                        @foreach ($roles as $r)
                                            <option value="{{ $r->id }}">{{ $r->role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-3" id="marketing-group" style="display: none;">
                                <label for="id_marketing" class="col-sm-3 col-form-label">Marketing</label>
                                <div class="col-sm-4">
                                    <select name="id_marketing" id="id_marketing" class="form-select select-marketing">
                                        <option value=""></option>
                                        @foreach ($marketing as $r)
                                            <option value="{{ $r->id }}">{{ $r->nama_marketing }}</option>
                                        @endforeach
                                    </select>
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
    @endif
@endsection
@push('scripts')
    <script>
        $(document).on('click', '[data-target="#modalForm"]', function() {
            $('#modalFormLabel').text('Tambah Pengguna');
        });

        $(document).ready(function() {
            $('#role').on('change', function() {
                const selectedRole = $(this).val();
                if (selectedRole === '2') {
                    $('#marketing-group').show();
                } else {
                    $('#marketing-group').hide();
                    $('#id_marketing').val('');
                }
            });
        });

        $(document).ready(function() {
            $('.select-status').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Pilih Status',
                minimumResultsForSearch: Infinity,
            });
            $('.select-role').select2({
                theme: 'bootstrap4',
                width: '100%',
                minimumResultsForSearch: Infinity,
                placeholder: 'Pilih Role',
            });
            $('.select-marketing').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Pilih Marketing',
            });
        });

        var permissions = @json($permissions);
        var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(function() {
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: "{{ route('pengaturan-pengguna.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'surname',
                        name: 'surname',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'username',
                        name: 'username',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: showActionColumn
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }]
            });
        });

        // Tombol edit
        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#modalFormLabel').text('Edit Pengguna');
                    $('#primary_id').val(response.data.id);
                    $('#surname').val(response.data.surname);
                    $('#username').val(response.data.username);
                    $('#role').val(response.data.id_role).trigger('change');
                    $('#id_marketing').val(response.data.id_marketing).trigger('change');
                    $('#password').val(response.data.password);
                    $('#email').val(response.data.email);
                    $('#status').val(response.data.status).trigger('change');
                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('#status').val('').trigger('change');
            $('#role').val('').trigger('change');
            $('#id_marketing').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
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
            let url = id ? '{{ route('pengaturan-pengguna.update', ['pengaturan_pengguna' => ':id']) }}'.replace(
                    ':id', id) :
                '{{ route('pengaturan-pengguna.store') }}';
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
                    let msg = id ? "Data berhasil diupdate!" : "Data berhasil ditambahkan!";
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

        $('#formUser').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#id_user').val();
            let url = '{{ route('pengaturan-pengguna.update-user', ['id' => ':id']) }}'.replace(':id', id);
            let method = 'PUT'

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
                    audio.play();
                    let msg = "Pengguna berhasil diupdate!";
                    toastr.success(msg, "BERHASIL", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
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
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });
    </script>
@endpush
