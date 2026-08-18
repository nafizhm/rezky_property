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
                                    <h3 class="font-weight-bold text-lg">
                                        Detail Akad Tanggal
                                        {{ \Carbon\Carbon::parse($akad->tgl_akad)->locale('id')->translatedFormat('d F Y') }}
                                    </h3>

                                    <div class="d-flex align-items-center">

                                        <a href="{{ route('akad.detail.pdf', ['id' => $akad]) }}" target="_blank"
                                            class="btn btn-danger btn-sm mr-2">
                                            <i class="fas fa-file-pdf"></i> Cetak PDF
                                        </a>

                                        <a href="{{ route('akad.detail.excel', $akad) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-file-excel"></i> Cetak Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formData">
                                    @csrf
                                    <input type="hidden" id="id_akad" name="id_akad" value="{{ $akad->id }}">

                                    <div class="row mt-2 mb-4">
                                        <label class="col-sm-1 col-form-label">Filter</label>
                                        <div class="col-sm-2">
                                            <select name="filter" id="filter" class="form-control select-filter">
                                                <option value="1">Semua</option>
                                                <option value="2">Dipilih</option>
                                            </select>
                                        </div>
                                    </div>
                                    <table class="table table-bordered w-100 small table-striped data-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="check-all">
                                                </th>
                                                <th>No</th>
                                                <th>Nama Lengkap</th>
                                                <th>Lokasi Rumah</th>
                                                <th class="text-center">IPH</th>
                                                <th class="text-center">SHGB</th>
                                                <th class="text-center">SSP</th>
                                                <th class="text-center">BPHTB</th>
                                                <th class="text-center">SIKUMBANG</th>
                                                <th class="text-center">DAFTAR SIKASEP</th>
                                                <th class="text-center">FOTO SIKASEP</th>
                                                <th class="text-center">TRILOGI</th>
                                                <th class="text-center" width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="mt-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-md btn-primary" id="submitBtn">
                                            <span class="spinner-border spinner-border-sm me-2 d-none"
                                                role="status"></span>
                                            <span class="button-text">Simpan Customer</span>
                                        </button>
                                    </div>
                                </form>
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
    </div>

    <div class="modal fade" id="modalHadir" tabindex="-1" aria-labelledby="modalHadirLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalHadirLabel">Form Kehadiran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formHadir">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id_detail_akad" name="id_detail_akad">
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Customer</label>
                            <div class="col-sm-9">
                                <input type="text" id="nama_lengkap" class="form-control" name="nama_lengkap" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Lokasi Rumah</label>
                            <div class="col-sm-9">
                                <input type="text" id="lokasi_rumah" class="form-control" name="lokasi_rumah" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jenis Akad</label>
                        <div class="col-sm-6">
                            <select class="form-select select-jenis-akad" name="jenis_akad" id="jenis_akad">
                                <option value=""></option>
                                <option value="AJB">AJB</option>
                                <option value="PPJB">PPJB</option>
                            </select>
                        </div>
                    </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 col-form-label">Keterangan (Opsional)</label>
                            <div class="col-sm-9">
                                <textarea id="keterangan" class="form-control" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary " id="hadirBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan Kehadiran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select-filter').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Filter",
                minimumResultsForSearch: -1,
            });
            $('.select-jenis-akad').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Jenis Akad",
            });
        });

        $(function() {
            let id = $('#id_akad').val();
            let url = '{{ route('akad.show', ['akad' => ':id']) }}'.replace(':id', id)

            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: {
                    url: url,
                    data: function(d) {
                        d.filter = $('#filter').val()
                    }
                },
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap',
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
                        data: 'iph',
                        name: 'iph',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'shgb',
                        name: 'shgb',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'ssp',
                        name: 'ssp',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'bphtb',
                        name: 'bphtb',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'sikumbang',
                        name: 'sikumbang',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'daftar_sikasep',
                        name: 'daftar_sikasep',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'foto_sikasep',
                        name: 'foto_sikasep',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'trilogi',
                        name: 'trilogi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: 1,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                }],
                drawCallback: function() {
                    $('#check-all').prop('checked', false)
                }
            })

            $('#filter').on('change', function() {
                table.ajax.reload()
            })

            $('#check-all').on('click', function() {
                let checked = this.checked
                $('.row-check').prop('checked', checked)
            })

            $(document).on('change', '.row-check', function() {
                let total = $('.row-check').length
                let checked = $('.row-check:checked').length

                if (total > 1 && total === checked) {
                    $('#check-all').prop('checked', true)
                } else {
                    $('#check-all').prop('checked', false)
                }
            })
        });

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#id_akad').val();
            let url = '{{ route('akad.seleksi-customer', ['id_akad' => ':id']) }}'.replace(':id', id);
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
                    audio.play();
                    let msg = "Seleksi Customer berhasil disimpan!";
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
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan Customer');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.hadir-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#id_detail_akad').val(response.data.id);
                    $('#nama_lengkap').val(response.data.customer.nama_lengkap);
                    $('#lokasi_rumah').val(response.data.customer.lokasi.nama_kavling + ' (' + response.data
                        .customer.kavling.kode_kavling + ')');

                    $('#modalHadir').modal('show');
                }
            });
        });

        $('#modalHadir').on('hidden.bs.modal', function() {
            $('#formHadir')[0].reset();
            $('#id_detail_akad').val('');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#hadirBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#formHadir').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#hadirBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#id_detail_akad').val();
            let url = '{{ route('akad.seleksi-customer.update-hadir', ['id_detail' => ':id']) }}'.replace(':id',
                id);
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
                    $('#modalHadir').modal('hide');
                    audio.play();
                    let msg = "Kehadiran berhasil disimpan!";
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
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan Kehadiran');
                    submitBtn.prop('disabled', false);
                }
            });
        });
    </script>
@endpush
