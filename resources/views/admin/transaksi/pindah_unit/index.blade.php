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
                                    <h3 class="font-weight-bold text-lg">Data Pindah Unit Rumah</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($permissions['tambah'])
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modalForm"><i class="fas fa-plus"></i>
                                                Pindah Unit</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped w-100 data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tanggal Pindah</th>
                                            <th>Customer</th>
                                            <th>Lokasi Lama</th>
                                            <th>Lokasi Baru</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
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

    <!-- Modal Create -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Form Pindah Unit</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tanggal Pindah</label>
                            <div class="col-md-3">
                                <input name="tgl_pindah" id="tgl_pindah" value="{{ $tanggalSekarang }}" class="form-control"
                                    type="date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Customer</label>
                            <div class="col-sm-5">
                                <select class="form-control select-customer" id="id_customer" name="id_customer">
                                    <option value=""></option>
                                    @foreach ($customers as $ctm)
                                        <option value="{{ $ctm->id }}">
                                            {{ $ctm->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nik" class="col-md-3 col-form-label">NIK</label>
                            <div class="col-md-5">
                                <input name="nik" id="nik" class="form-control" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="alamat_ktp" class="col-md-3 col-form-label">Alamat</label>
                            <div class="col-md-6">
                                <textarea name="alamat_ktp" class="form-control" id="alamat_ktp" rows="2" readonly></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kavling" class="col-md-3 col-form-label">Lokasi Unit Lama</label>
                            <div class="col-md-4">
                                <input name="id_kavling_lama" id="id_kavling_lama" class="form-control" type="text"
                                    readonly>
                            </div>
                            <label for="nominal_utj" class="col-md-2 col-form-label">Nominal UTJ</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="nominal_utj" id="nominal_utj" class="form-control format-number"
                                        type="text" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lokasi Unit Baru</label>
                            <div class="col-sm-4">
                                <select id="id_kavling_baru" class="form-control select-kavling" name="id_kavling_baru">
                                    <option value=""></option>
                                    @foreach ($kavBaru as $kav)
                                        <option value="{{ $kav->id }}">
                                            {{ $kav->kode_kavling }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="keterangan" class="col-md-3 col-form-label">Keterangan</label>
                            <div class="col-md-9">
                                <textarea name="keterangan_pindah" class="form-control" id="keterangan_pindah" rows="3"></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Biaya Administrasi</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="biaya_admin" class="form-control format-number" type="text"
                                        id="biaya_admin">
                                </div>
                            </div>
                            <label class="col-md-2 col-form-label">Rekening</label>
                            <div class="col-md-4">
                                <select name="id_bank" id="id_bank" class="form-control select-bank">
                                    <option value=""></option>
                                    @foreach ($bankList as $data)
                                        <option value="{{ $data->id }}">
                                            {{ $data->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-md-3">Metode Pembayaran</label>
                            <div class="col-md-4">
                                <select name="id_metode_bayar" id="id_metode_bayar"
                                    class="form-control select-metode-bayar">
                                    <option value=""></option>
                                    @foreach ($metodeBayar as $bayar)
                                        <option value="{{ $bayar->id }}">
                                            {{ $bayar->jenis_bayar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bukti Pembayaran</label>
                            <div class="col-sm-4">
                                <input type="file" class="mb-2" id="lampiran_bukti" name="lampiran_bukti"
                                    accept=".jpg, .jpeg, .png, .pdf">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewBuktiBayar"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada berkas</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
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
        previewFile('lampiran_bukti', 'previewBuktiBayar');

        $(document).ready(function() {
            $('.select-customer').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Customer",
            });
            $('.select-kavling').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Kavling Baru",
            });
            $('.select-bank').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: -1,
                placeholder: "Pilih Bank",
            });
            $('.select-metode-bayar').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: -1,
                placeholder: "Pilih Metode Pembayaran",
            });
        });

        const permissions = @json($permissions);
        const showActionColumn = permissions['edit'] == 1 || permissions['hapus'] == 1;

        $(function() {
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: "{{ route('pindah-unit.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: true,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'tgl_pindah',
                        name: 'tgl_pindah',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer.nama_lengkap',
                        name: 'customer.nama_lengkap',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'lokasi_lama',
                        name: 'lokasi_lama',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'lokasi_baru',
                        name: 'lokasi_baru',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'keterangan_pindah',
                        name: 'keterangan_pindah',
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
                }]
            });
        });

        $(document).ready(function() {

            $('#id_customer').on('change', function() {
                const customerId = $(this).val();

                if (!customerId) {
                    resetCustomerDetail();
                    return;
                }

                $.ajax({
                    url: "{{ route('pindah-unit.detail-customer', ':id') }}".replace(':id',
                        customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            const customer = res.customer;

                            $('#nik').val(customer.nik ?? '');
                            $('#alamat_ktp').val(customer.alamat_ktp ?? '');

                            let lokasi = '-';
                            if (customer.lokasi && customer.kavling) {
                                lokasi = customer.lokasi.nama_kavling + ' - ' + customer.kavling
                                    .kode_kavling;
                            }

                            $('#id_kavling_lama').val(lokasi);

                            $('#nominal_utj').val(formatNumber(res.utj));
                        }
                    },
                    error: function() {
                        resetCustomerDetail();
                    }
                });
            });

            function resetCustomerDetail() {
                $('#nik').val('');
                $('#alamat_ktp').val('');
                $('#id_kavling_lama').val('');
                $('#nominal_utj').val('');
            }
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $('#primary_id').val('');
            $('#id_customer').val('').trigger('change');

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            const previews = [
                '#previewBuktiBayar',
            ];

            previews.forEach(id => {
                $(id).html('<span style="color: #6c757d;">Tidak ada berkas</span>');
            });
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
            let url = id ? '{{ route('pindah-unit.update', ['pindah_unit' => ':id']) }}'.replace(':id', id) :
                '{{ route('pindah-unit.store') }}';
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
                    let msg = id ? "Pindah Unit berhasil diupdate!" :
                        "Pindah Unit berhasil ditambahkan!";
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

        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Pindah Unit ini akan dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="swal-btn-text">Ya, Batalkan</span>',
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
                                toastr.success("Pindah Unit telah dibatalkan!",
                                    "BERHASIL", {
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
