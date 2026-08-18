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
                                    <h3 class="font-weight-bold text-xl">Data Rekening Listrik & Air</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($permissions['tambah'])
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modalForm"><i class="fas fa-plus"></i>
                                                Tambah Listrik & Air</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-bordered table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="30px">No</th>
                                            <th>Lokasi Rumah</th>
                                            <th>No. Rek. Listrik</th>
                                            <th>No. Rek. Air</th>
                                            <th class="text-center" width="100px">Action</th>
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
        <div class="modal-dialog modal-xl" role="document">
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
                            <label for="id_lokasi" class="col-sm-3 col-form-label">Lokasi Perumahan</label>
                            <div class="col-sm-3">
                                <select name="id_lokasi" id="id_lokasi" class="form-control select-lokasi">
                                    <option value=""></option>
                                    @foreach ($lokasi as $l)
                                        <option value="{{ $l->id }}">{{ $l->nama_kavling }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="id_kavling" class="col-sm-1 col-form-label">Blok/Kav</label>
                            <div class="col-sm-2">
                                <select name="id_kavling" id="id_kavling" class="form-control select-kavling"></select>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row mb-3">
                            <label for="nama" class="col-sm-3 col-form-label">No. Rekening Listrik</label>
                            <div class="col-sm-3">
                                <input type="text" id="norek_listrik" class="form-control" name="norek_listrik">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Foto Meteran Listrik 1</label>
                            <div class="col-sm-3">
                                <input type="file" class="mb-2" id="foto_listrik" name="foto_listrik"
                                    accept=".jpg, .jpeg, .png">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewFotoListrik1"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada foto</span>
                                </div>
                            </div>
                            <label class="col-sm-3 col-form-label">Foto Meteran Listrik 2</label>
                            <div class="col-sm-3">
                                <input type="file" class="mb-2" id="foto_listrik_2" name="foto_listrik_2"
                                    accept=".jpg, .jpeg, .png">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewFotoListrik2"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada foto</span>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="norek_air">No. Rekening Air</label>
                            <div class="col-sm-3">
                                <input id="norek_air" name="norek_air" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Foto Meteran Air 1</label>
                            <div class="col-sm-3">
                                <input type="file" class="mb-2" id="foto_air" name="foto_air"
                                    accept=".jpg, .jpeg, .png">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewFotoAir1"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada foto</span>
                                </div>
                            </div>
                            <label class="col-sm-3 col-form-label">Foto Meteran Air 2</label>
                            <div class="col-sm-3">
                                <input type="file" class="mb-2" id="foto_air_2" name="foto_air_2"
                                    accept=".jpg, .jpeg, .png">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewFotoAir2"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada foto</span>
                                </div>
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
        previewFile('foto_listrik', 'previewFotoListrik1');
        previewFile('foto_listrik_2', 'previewFotoListrik2');
        previewFile('foto_air', 'previewFotoAir1');
        previewFile('foto_air_2', 'previewFotoAir2');

        $(document).ready(function() {
            $('.select-lokasi').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Lokasi",
            });
            $('.select-kavling').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Kavling",
            });
        });

        $(document).on('click', '[data-target="#modalForm"]', function() {
            $('#modalFormLabel').text('Tambah Listrik dan Air');
        });

        const routeGetKavling = "{{ route('customer.getKavling', ':id') }}";

        $('#id_lokasi').on('change', function() {
            let idLokasi = $(this).val();
            $('#id_kavling').html('<option value="">Loading...</option>').trigger('change');

            if (idLokasi) {
                const urlKavling = routeGetKavling.replace(':id', idLokasi);
                $.get(urlKavling, function(data) {
                    let options = '<option value=""></option>';
                    data.forEach(function(item) {
                        options +=
                            `<option value="${item.id}">${item.kode_kavling}</option>`;
                    });
                    $('#id_kavling').html(options).trigger('change');
                });
            }
        });

        $(function() {
            var permissions = @json($permissions);
            var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: "{{ route('listrik-air.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'lokasi_rumah',
                        name: 'lokasi_rumah',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'norek_listrik',
                        name: 'norek_listrik',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'norek_air',
                        name: 'norek_air',
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
        });

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#modalFormLabel').text('Edit Listrik dan Air');
                    $('#primary_id').val(response.data.id);
                    $('#id_lokasi').val(response.data.id_lokasi).trigger('change');

                    setTimeout(function() {
                        $('#id_kavling').val(response.data.id_kavling).trigger('change');
                    }, 300);

                    $('#norek_listrik').val(response.data.norek_listrik);
                    $('#norek_air').val(response.data.norek_air);

                    setPreview(response.data.foto_listrik, 'assets/legal/listrik_air/listrik_1',
                        'previewFotoListrik1');
                    setPreview(response.data.foto_listrik_2, 'assets/legal/listrik_air/listrik_2',
                        'previewFotoListrik2');
                    setPreview(response.data.foto_air, 'assets/legal/listrik_air/air_1', 'previewFotoAir1');
                    setPreview(response.data.foto_air_2, 'assets/legal/listrik_air/air_2',
                        'previewFotoAir2');

                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('.select-lokasi').val('').trigger('change');
            $('.select-kavling').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            const previews = [
                '#previewFotoListrik1',
                '#previewFotoListrik2',
                '#previewFotoAir1',
                '#previewFotoAir2'
            ];

            previews.forEach(id => {
                $(id).html('<span style="color: #6c757d;">Tidak ada berkas</span>');
            });
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
            let url = id ? '{{ route('listrik-air.update', ['listrik_air' => ':id']) }}'.replace(':id', id) :
                '{{ route('listrik-air.store') }}';
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
                    toastr.success("Data telah disimpan!", "BERHASIL", {
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
