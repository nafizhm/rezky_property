@extends('admin.layout_admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
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
                                    <h3 class="font-weight-bold text-lg">Hak Akses</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row mb-4">
                                    <label for="" class="col-md-1 col-form-label">Pengguna</label>
                                    <div class="col-sm-3">
                                        <select id="pilih-pengguna" class="form-select select-pengguna">
                                            <option value=""></option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="50px">No</th>
                                            <th>ID</th>
                                            <th width="200px">Induk Menu</th>
                                            <th>Judul Menu</th>
                                            <th>Route/Url</th>
                                            <th width="70px">Lihat</th>
                                            <th width="70px">Tambah</th>
                                            <th width="70px">Edit</th>
                                            <th width="70px">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <!-- Tombol Simpan -->
                                @if ($permissions['edit'] == 1)
                                    <div class="text-left mt-4">
                                        <button class="btn btn-primary" disabled id="btn-simpan">
                                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                                aria-hidden="true"></span>
                                            <span class="button-text">Simpan Hak Akses</span>
                                        </button>
                                    </div>
                                @endif
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select-pengguna').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Pilih Pengguna',
            });
        });

        $(document).ready(function() {
            const selectedUserId = localStorage.getItem('selectedUserId');
            if (selectedUserId) {
                $('#pilih-pengguna').val(selectedUserId).trigger('change');
                localStorage.removeItem('selectedUserId');
            }

            const successMsg = localStorage.getItem('hakAksesSuccess');
            if (successMsg) {
                audio.play();
                toastr.success(successMsg, "BERHASIL", {
                    progressBar: true,
                    timeOut: 3500,
                    positionClass: "toast-bottom-right",
                });
                localStorage.removeItem('hakAksesSuccess');
            }

        });

        var btnSimpan = $('#btn-simpan');

        btnSimpan.prop('disabled', true);

        $('#pilih-pengguna').change(function() {
            if ($(this).val()) {
                btnSimpan.prop('disabled', false);
            } else {
                btnSimpan.prop('disabled', true);
            }
        });

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        var permissions = @json($permissions);

        var hakAksesState = {
            lihat: {},
            tambah: {},
            edit: {},
            hapus: {}
        };

        var table = $('.data-table').DataTable({
            processing: false,
            serverSide: true,
            ordering: false,
            paging: false,
            lengthChange: false,
            responsive: true,
            ajax: {
                url: "{{ route('admin.getHakAkses') }}",
                data: function(d) {
                    d.id_user = $('#pilih-pengguna').val();
                    d.permissions = permissions;
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
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    visible: false
                },
                {
                    data: 'induk_menu',
                    name: 'induk_menu',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'route_name',
                    name: 'route_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'lihat',
                    name: 'lihat',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tambah',
                    name: 'tambah',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'edit',
                    name: 'edit',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'hapus',
                    name: 'hapus',
                    orderable: false,
                    searchable: false
                }
            ],
            columnDefs: [{
                targets: 0,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }]
        });


        $('#pilih-pengguna').change(function() {
            table.draw();
        });


        $('.data-table').on('draw.dt', function() {
            $('.data-table tbody').find('input[type="checkbox"]').each(function() {
                let name = $(this).attr('name');
                let match = name.match(/(\w+)\[(\d+)\]/);
                if (match) {
                    let type = match[1];
                    let id = match[2];
                    if (hakAksesState[type] && hakAksesState[type][id] !== undefined) {
                        $(this).prop('checked', hakAksesState[type][id] == 1);
                    }
                }
            });
        });

        $(document).on('change', '.data-table tbody input[type="checkbox"]', function() {
            let name = $(this).attr('name');
            let match = name.match(/(\w+)\[(\d+)\]/);
            if (match) {
                let type = match[1];
                let id = match[2];
                hakAksesState[type][id] = $(this).is(':checked') ? 1 : 0;
            }
        });

        btnSimpan.click(function() {
            let submitBtn = $('#btn-simpan');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            var id_user = $('#pilih-pengguna').val();

            $('.data-table tbody input[type="checkbox"]').each(function() {
                let name = $(this).attr('name');
                let match = name.match(/(\w+)\[(\d+)\]/);
                if (match) {
                    let type = match[1];
                    let id = match[2];
                    hakAksesState[type][id] = $(this).is(':checked') ? 1 : 0;
                }
            });

            $.ajax({
                url: '{{ route('admin.updateHakAkses') }}',
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_user: id_user,
                    hak_akses_data: hakAksesState
                },
                success: function(response) {
                    if (response.success) {
                        localStorage.setItem('hakAksesSuccess', response.message);
                        localStorage.setItem('selectedUserId', id_user);
                        location.reload();
                    } else {
                        audio.play();
                        toastr.error("Terjadi kesalahan saat memperbarui Hak Akses.", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                    }
                },
                error: function() {
                    toastr.error("Terjadi kesalahan saat menghubungi server.", "GAGAL!", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                    spinner.addClass('d-none');
                    btnText.text('Simpan Hak Akses');
                    submitBtn.prop('disabled', false);
                }
            });
        });
    </script>
@endpush
