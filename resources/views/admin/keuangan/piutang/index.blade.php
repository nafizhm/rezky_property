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
                            <div class="card-header">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Data Piutang</h3>
                                    <div class="d-flex align-items-center">
                                        @if (isset($permissions['tambah']) && $permissions['tambah'] == 1)
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modalForm">
                                                <i class="fas fa-plus"></i> Tambah Piutang
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-lg-2 ms-1">
                                        <fieldset class="form-group">
                                            <input type="date" class="form-control" id="filter_tanggal"
                                                name="filter_tanggal" required>
                                        </fieldset>
                                    </div>
                                </div>
                                <table class="table data-table w-100 table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Piutang</th>
                                            <th width="250px">Deskripsi</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Tanggal Pelunasan</th>
                                            <th width="100px" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
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
                            <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Piutang</label>
                            <div class="col-sm-5">
                                <input type="date" class="form-control" id="tanggal_piutang" name="tanggal_piutang"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id_bank" class="col-sm-3 col-form-label">Rekening</label>
                            <div class="col-sm-3">
                                <select class="form-select select-bank" name="id_bank" id="id_bank">
                                    <option value=""></option>
                                    @foreach ($bankList as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="nominal" class="col-sm-2 col-form-label">Nominal</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="nominal" id="nominal" class="form-control format-number" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lampiran</label>
                            <div class="col-sm-4">
                                <input type="file" class="mb-2" id="lampiran" name="lampiran"
                                    accept=".jpg, .jpeg, .png">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewLampiran"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada berkas</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                            </div>
                        </div>

                        <div id="info-pembayaran" style="display: none;">
                            <!-- Sisa Bayar -->
                            <div class="form-group row">
                                <label for="sisa_bayar" class="col-sm-3 col-form-label">Sisa Bayar</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" class="form-control" id="sisa_bayar" name="sisa_bayar"
                                            disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Terbayar -->
                            <div class="form-group row mt-3">
                                <label for="terbayar" class="col-sm-3 col-form-label">Terbayar</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" class="form-control" id="terbayar" name="terbayar"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="button-text">Batal</span>
                        </button>
                        <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalDetailLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalDetailLabel"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Piutang</label>
                        <div class="col-sm-5">
                            <input type="date" class="form-control" id="tanggal_piutang_detail"
                                name="tanggal_piutang" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_bank" class="col-sm-3 col-form-label">Rekening</label>
                        <div class="col-sm-3">
                            <select class="form-select select-bank" name="id_bank" id="id_bank_detail" readonly>
                                <option value=""></option>
                                @foreach ($bankList as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="nominal" class="col-sm-2 col-form-label">Nominal</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input name="nominal" id="nominal_detail" class="form-control format-number"
                                    type="text" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Lampiran</label>
                        <div class="col-sm-4">
                            <input type="file" class="mb-2" id="lampiran_detail" name="lampiran_detail"
                                accept=".jpg, .jpeg, .png">
                            <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                id="previewLampiranDetail"
                                style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                <span style="color: #6c757d;">Tidak ada berkas</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="deskripsi_detail" name="deskripsi" rows="3" readonly></textarea>
                        </div>
                    </div>

                    <div id="info-pembayaran-detail" style="display: none;">
                        <!-- Sisa Bayar -->
                        <div class="form-group row">
                            <label for="sisa_bayar" class="col-sm-3 col-form-label">Sisa Bayar</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control" id="sisa_bayar_detail" name="sisa_bayar"
                                        disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Terbayar -->
                        <div class="form-group row mt-3">
                            <label for="terbayar" class="col-sm-3 col-form-label">Terbayar</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control" id="terbayar_detail" name="terbayar"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <span class="button-text">Keluar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        previewFile('lampiran', 'previewLampiran');
        previewFile('lampiran_detail', 'previewLampiranDetail');

        $(document).on('click', '[data-target="#modalForm"]', function() {
            $('#modalFormLabel').text('Tambah Piutang');
        });

        $(document).ready(function() {
            $('.select-bank').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Rekening',
                minimumResultsForSearch: Infinity,
            });
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
                    url: "{{ route('piutang.index') }}",
                    data: function(d) {
                        d.filter_tanggal = $('#filter_tanggal').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal_piutang',
                        name: 'tanggal_piutang',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'nominal',
                        name: 'nominal',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tgl_pelunasan',
                        name: 'tgl_pelunasan',
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
            $('#filter_tanggal').on('change', function() {
                table.ajax.reload();
            });
        });

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#modalFormLabel').text('Edit Piutang');
                    $('#primary_id').val(response.data.id);
                    $('#tanggal_piutang').val(response.data.tanggal_piutang);
                    $('#deskripsi').val(response.data.deskripsi);

                    let nominal = parseFloat(response.data.nominal);
                    if (!isNaN(nominal)) {
                        $('#nominal').val(nominal.toLocaleString('id-ID'));
                    } else {
                        $('#nominal').val('');
                    }

                    setPreview(response.data.lampiran, 'assets/keuangan/pengeluaran', 'previewLampiran');

                    const terbayar = parseFloat(response.data.terbayar) || 0;
                    const sisaBayar = parseFloat(response.data.sisa_bayar) || 0;

                    $('#terbayar').val(terbayar.toLocaleString('id-ID'));
                    $('#sisa_bayar').val(sisaBayar.toLocaleString('id-ID'));
                    $('#id_bank').val(response.data.id_bank).trigger('change');

                    $('#info-pembayaran').show();

                    $('#modalForm').modal('show');
                }
            });
        });

        $(document).on('click', '.detail-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#modalDetailLabel').text('Detail Piutang');
                    $('#tanggal_piutang_detail').val(response.data.tanggal_piutang);
                    $('#deskripsi_detail').val(response.data.deskripsi);

                    let nominal = parseFloat(response.data.nominal);
                    if (!isNaN(nominal)) {
                        $('#nominal_detail').val(nominal.toLocaleString('id-ID'));
                    } else {
                        $('#nominal_detail').val('');
                    }

                    setPreview(response.data.lampiran, 'assets/keuangan/pengeluaran',
                        'previewLampiranDetail');

                    const terbayar = parseFloat(response.data.terbayar) || 0;
                    const sisaBayar = parseFloat(response.data.sisa_bayar) || 0;

                    $('#terbayar_detail').val(terbayar.toLocaleString('id-ID'));
                    $('#sisa_bayar_detail').val(sisaBayar.toLocaleString('id-ID'));
                    $('#id_bank_detail').val(response.data.id_bank).trigger('change');

                    $('#id_bank_detail').prop('disabled', true);
                    $('#lampiran_detail').prop('disabled', true);

                    $('#info-pembayaran-detail').show();

                    $('#modalDetail').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('#id_bank').val('').trigger('change');
            $('#info-pembayaran').hide();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            $('#previewLampiran').html(`<span style="color: #6c757d;">Tidak ada berkas</span>`);
        });

        $('#modalDetail').on('hidden.bs.modal', function() {
            $('#id_bank_detail').val('').trigger('change');
            $('#info-pembayaran-detail').hide();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $('#previewLampiranDetail').html(`<span style="color: #6c757d;">Tidak ada berkas</span>`);
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
            let url = id ? '{{ route('piutang.update', ['piutang' => ':id']) }}'.replace(':id', id) :
                '{{ route('piutang.store') }}';
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
                    let msg = id ? "Piutang berhasil diupdate!" : "Piutang berhasil ditambahkan!";
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
                            `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menghapus...`;
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

        function formatRupiah(input) {
            let value = input.value.replace(/\D/g, '');
            if (!value) {
                input.value = '';
                return;
            }

            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>
@endpush
