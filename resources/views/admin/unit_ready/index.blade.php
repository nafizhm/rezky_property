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
                                    <h3 class="font-weight-bold text-lg">Data Unit Ready</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($permissions['edit'])
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modalForm">Edit Unit Ready
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-lg-2 ms-1">
                                        <fieldset class="form-group">
                                            <select name="perumahan_filter" id="perumahan_filter"
                                                class="form-control select-perumahan">
                                                <option value=""></option>
                                                @foreach ($lokasiList as $lokasi)
                                                    <option value="{{ $lokasi->id }}">{{ $lokasi->nama_kavling }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-2 ms-1">
                                        <fieldset class="form-group">
                                            <select name="status_filter" id="status_filter"
                                                class="form-control select-status">
                                                <option value=""></option>
                                                <option value="1">Belum Mulai</option>
                                                <option value="2">Belum Ready</option>
                                                <option value="3">Ready</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped data-table w-100">
                                    <thead>
                                        <tr>
                                            <th width="30px">No</th>
                                            <th>Perumahan</th>
                                            <th>Kode Kavling</th>
                                            <th>Status Ready</th>
                                            <th>Keterangan</th>
                                            <th width="100px">Action</th>
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

        <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false"
            aria-labelledby="modalFormLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Lokasi Proyek</label>
                                <div class="col-sm-5">
                                    <select name="id_lokasi" id="id_lokasi" class="form-control select-lokasi">
                                        <option value=""></option>
                                        @foreach ($lokasiList as $lokasi)
                                            <option value="{{ $lokasi->id }}">{{ $lokasi->nama_kavling }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Blok</label>
                                <div class="col-sm-5">
                                    <select name="blok[]" id="blok" class="form-control select-blok"
                                        multiple="multiple" data-placeholder="Pilih Blok">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Unit Rumah</label>
                                <div class="col-sm-4">
                                    <div id="unit-rumah-wrapper" class="border rounded p-2"
                                        style="max-height: 200px; overflow-y: auto; height: 200px;"></div>
                                </div>
                                <label class="col-sm-2 col-form-label">Jumlah Unit</label>
                                <div class="col-sm-3">
                                    <input type="text" id="jumlah_unit" name="jumlah_unit" class="form-control" readonly>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-4">
                                    <select name="status_ready" id="status_ready" class="form-control select-status">
                                        <option value=""></option>
                                        <option value="1">Belum Mulai</option>
                                        <option value="2">Belum Ready</option>
                                        <option value="3">Ready</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea name="keterangan" class="form-control" id="keterangan" rows="4"></textarea>
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

        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" data-focus="false"
            aria-labelledby="modalEditLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-indigo">
                        <h5 class="modal-title text-white font-weight-bold" id="modalEditLabel"></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formEdit">
                        @csrf
                        <input type="hidden" id="primary_id" name="primary_id">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Perumahan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="perumahan" id="perumahan" class="form-control" disabled>
                                </div>
                                <label class="col-sm-2 col-form-label">Blok/Kav</label>
                                <div class="col-sm-3">
                                    <input type="text" name="blok_kav" id="blok_kav" class="form-control" disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-4">
                                    <select name="status_edit" id="status_edit" class="form-control select-status">
                                        <option value=""></option>
                                        <option value="1">Belum Mulai</option>
                                        <option value="2">Belum Ready</option>
                                        <option value="3">Ready</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea name="keterangan_edit" class="form-control" id="keterangan_edit" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary ms-1" id="submitEdit">
                                    <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="button-text">Simpan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '[data-target="#modalForm"]', function() {
            $('#modalFormLabel').text('Edit Unit Ready');
        });

        $(document).ready(function() {
            $('.select-perumahan').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Perumahan",
            });
            $('.select-lokasi').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Lokasi Proyek",
            });
            $('.select-blok').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Blok",
            });
            $('.select-status').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status",
                minimumResultsForSearch: Infinity
            });
        });

        $('#id_lokasi').on('change', function() {
            let id = $(this).val();
            $('#blok').empty().trigger('change'); // kosongkan dan reset
            $('#unit-rumah-wrapper').empty();
            $('#jumlah_unit').val(0);

            if (id) {
                $.get('{{ url('get-blok-by-lokasi') }}/' + id, function(data) {
                    data.forEach(function(val) {
                        $('#blok').append('<option value="' + val + '">' + val + '</option>');
                    });
                    $('#blok').trigger('change.select2'); // refresh select2
                });
            }
        });

        // Fetch unit rumah setiap blok berubah
        $('#blok').on('change', function() {
            fetchUnitRumah();
        });

        function fetchUnitRumah() {
            const id_lokasi = $('#id_lokasi').val();
            const blokList = $('#blok').val(); // array blok terpilih

            if (!id_lokasi || !blokList.length) {
                $('#unit-rumah-wrapper').empty();
                $('#jumlah_unit').val(0);
                return;
            }

            $.get('/get-unit-by-lokasi-blok', {
                id_lokasi: id_lokasi,
                blok: blokList // kirim array
            }, function(data) {
                let html = '';
                data.forEach(function(kode) {
                    html += `
                <div class="form-check">
                    <input class="form-check-input unit-checkbox" type="checkbox" name="unit_rumah[]" value="${kode}" id="unit_${kode}">
                    <label class="form-check-label" for="unit_${kode}">${kode}</label>
                </div>
            `;
                });
                $('#unit-rumah-wrapper').html(html);
                updateJumlahUnit();
                updateVolumePekerjaan();
            });
        }

        function updateJumlahUnit() {
            const totalChecked = $('.unit-checkbox:checked').length;
            $('#jumlah_unit').val(totalChecked);
        }

        $(document).on('change', '#id_lokasi, #blok', fetchUnitRumah);

        $(document).on('change', '.unit-checkbox', function() {
            updateJumlahUnit();
        });

        var permissions = @json($permissions);
        var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

        $(function() {
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: {
                    url: "{{ route('unit-ready.index') }}",
                    data: function(d) {
                        d.status_filter = $('#status_filter').val();
                        d.perumahan_filter = $('#perumahan_filter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id_lokasi',
                        name: 'id_lokasi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_kavling',
                        name: 'kode_kavling',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'status_ready',
                        name: 'status_ready',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        orderable: false,
                        searchable: true
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
                }]
            });

            $('#status_filter, #perumahan_filter').change(function() {
                table.ajax.reload();
            });
        });

        $(document).on('click', '.edit-button', async function() {
            var url = $(this).data('url');

            $.get(url, async function(response) {
                if (response.status === 'success') {
                    $('#modalEditLabel').text('Edit Unit Ready');
                    $('#primary_id').val(response.data.id);
                    $('#perumahan').val(response.data.lokasi.nama_kavling);
                    $('#blok_kav').val(response.data.kode_kavling);
                    $('#status_edit').val(response.data.status_ready).trigger('change');
                    $('#keterangan_edit').val(response.data.keterangan);

                    $('#modalEdit').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.select-status').val('').trigger('change');
            $('#unit-rumah-wrapper').empty();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#modalEdit').on('hidden.bs.modal', function() {
            $('#formEdit')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.select-status').val('').trigger('change');

            let submitBtn = $('#submitEdit');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
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
            let url = '{{ route('unit-ready.store') }}';
            let method = 'POST';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);
            formData.append('_method', method);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalForm').modal('hide');
                        audio.play();
                        let msg = "Unit Rumah berhasil diupdate!";
                        toastr.success(msg, "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                        $('.data-table').DataTable().ajax.reload();
                    }
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
                            if (key.includes('.')) {
                                let parts = key.split('.');
                                let field = parts[0];
                                let index = parseInt(parts[1]);

                                let inputSelector;

                                if ($(`[name="${field}[]"]`).length > 0) {
                                    inputSelector = $(`[name="${field}[]"]`).eq(index);
                                } else {
                                    return;
                                }

                                inputSelector.addClass('is-invalid');
                                inputSelector.closest('.form-control, .form-select').parent()
                                    .find('.invalid-feedback').remove();
                                inputSelector.closest('.form-control, .form-select').parent()
                                    .append(
                                        `<span class="invalid-feedback" role="alert"><strong>${val[0]}</strong></span>`
                                    );
                            } else {
                                let input = $('#' + key);
                                input.addClass('is-invalid');
                                input.parent().find('.invalid-feedback').remove();
                                input.parent().append(
                                    '<span class="invalid-feedback" role="alert"><strong>' +
                                    val[0] + '</strong></span>'
                                );
                            }
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

        $('#formEdit').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitEdit');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = '{{ route('unit-ready.update', ['unit_ready' => ':id']) }}'.replace(':id', id);
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
                    if (response.success) {
                        $('#modalEdit').modal('hide');
                        audio.play();
                        let msg = "Unit Rumah berhasil diupdate!";
                        toastr.success(msg, "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                        $('.data-table').DataTable().ajax.reload();
                    }
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
                            if (key.includes('.')) {
                                let parts = key.split('.');
                                let field = parts[0];
                                let index = parseInt(parts[1]);

                                let inputSelector;

                                if ($(`[name="${field}[]"]`).length > 0) {
                                    inputSelector = $(`[name="${field}[]"]`).eq(index);
                                } else {
                                    return;
                                }

                                inputSelector.addClass('is-invalid');
                                inputSelector.closest('.form-control, .form-select').parent()
                                    .find('.invalid-feedback').remove();
                                inputSelector.closest('.form-control, .form-select').parent()
                                    .append(
                                        `<span class="invalid-feedback" role="alert"><strong>${val[0]}</strong></span>`
                                    );
                            } else {
                                let input = $('#' + key);
                                input.addClass('is-invalid');
                                input.parent().find('.invalid-feedback').remove();
                                input.parent().append(
                                    '<span class="invalid-feedback" role="alert"><strong>' +
                                    val[0] + '</strong></span>'
                                );
                            }
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
