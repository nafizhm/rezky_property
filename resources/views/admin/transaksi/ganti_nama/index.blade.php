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
                                    <h3 class="font-weight-bold text-lg">Data Ganti Nama</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($permissions['tambah'])
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modalForm"><i class="fas fa-plus"></i>
                                                Ganti Nama</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped w-100 data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tanggal Ganti Nama</th>
                                            <th>Customer Lama</th>
                                            <th>Customer Baru</th>
                                            <th>Lokasi Unit</th>
                                            <th>Biaya</th>
                                            <th>Keterangan</th>
                                            <th width="10%">Action</th>
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
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Form Ganti Nama</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tanggal Ganti Nama</label>
                            <div class="col-md-3">
                                <input name="tgl_ganti" id="tgl_ganti" value="{{ $tanggalSekarang }}" class="form-control"
                                    type="date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Customer Lama</label>
                            <div class="col-sm-5">
                                <select class="form-control select-customer" id="id_customer_lama" name="id_customer_lama">
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
                            <label class="col-md-3 col-form-label">NIK</label>
                            <div class="col-md-5">
                                <input name="nik_lama" id="nik_lama" class="form-control" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="alamat_ktp" class="col-md-3 col-form-label">Alamat</label>
                            <div class="col-md-6">
                                <textarea name="alamat_ktp_lama" class="form-control" id="alamat_ktp_lama" rows="2" readonly></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kavling" class="col-md-3 col-form-label">Lokasi Unit</label>
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
                            <label for="keterangan" class="col-md-3 col-form-label">Keterangan</label>
                            <div class="col-md-9">
                                <textarea name="keterangan_ganti" class="form-control" id="keterangan_ganti" rows="3"></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="nama_lengkap" class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                            <div class="col-sm-3">
                                <input type="number" name="nik" id="nik" class="form-control">
                            </div>
                            <label for="nik_p" class="col-sm-3 col-form-label">NIK Pasangan</label>
                            <div class="col-sm-3">
                                <input type="number" name="nik_p" id="nik_p" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-3">
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control">
                            </div>

                            <label for="tgl_lahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-3">
                                <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-3">
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control select-jk">
                                    <option value=""></option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <label for="no_telp" class="col-sm-3 col-form-label">No. Telp / WA</label>
                            <div class="col-sm-3">
                                <input type="number" name="no_telp" id="no_telp" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-3">
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
                            <div class="col-sm-3">
                                <input type="text" name="npwp" id="npwp" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="alamat_domisili" class="col-sm-3 col-form-label">Alamat Domisili</label>
                            <div class="col-sm-3">
                                <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="3"></textarea>
                            </div>
                            <label for="alamat_ktp" class="col-sm-3 col-form-label">Alamat KTP</label>
                            <div class="col-sm-3">
                                <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pekerjaan" class="col-sm-3 col-form-label">Pekerjaan</label>
                            <div class="col-sm-4">
                                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control">
                            </div>

                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Biaya Ganti Nama</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="biaya_ganti_nama" class="form-control format-number" type="text"
                                        id="biaya_ganti_nama">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Metode Bayar</label>
                            <div class="col-md-3">
                                <select name="id_metode_bayar" id="id_metode_bayar"
                                    class="form-control select-metode-bayar">
                                    <option value=""></option>
                                    @foreach ($metodeList as $data)
                                        <option value="{{ $data->id }}">
                                            {{ $data->jenis_bayar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-2 col-form-label">Rekening</label>
                            <div class="col-md-4">
                                <select name="id_bank" id="id_bank" class="form-control select-bank">
                                    <option value=""></option>
                                    @foreach ($rekeningList as $data)
                                        <option value="{{ $data->id }}">
                                            {{ $data->nama }}
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
            $('.select-jk').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Jenis Kelamin",
                minimumResultsForSearch: -1,
            });
            $('.select-kavling').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Kavling Baru",
            });
            $('.select-bank').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: -1,
                placeholder: "Pilih Rekening",
            });
            $('.select-metode-bayar').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: -1,
                placeholder: "Pilih Metode Bayar",
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
                ajax: "{{ route('ganti-nama.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: true,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'tgl_ganti',
                        name: 'tgl_ganti',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_lama',
                        name: 'customer_lama',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'customer_baru',
                        name: 'customer_baru',
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
                        data: 'biaya_ganti_nama',
                        name: 'biaya_ganti_nama',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'keterangan_ganti',
                        name: 'keterangan_ganti',
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

            $('#id_customer_lama').on('change', function() {
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

                            $('#nik_lama').val(customer.nik ?? '');
                            $('#alamat_ktp_lama').val(customer.alamat_ktp ?? '');

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
                $('#nik_lama').val('');
                $('#alamat_ktp_lama').val('');
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
            $('#id_bank_kpr').val('').trigger('change');

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
            let url = id ? '{{ route('ganti-nama.update', ['ganti_nama' => ':id']) }}'.replace(':id',
                    id) :
                '{{ route('ganti-nama.store') }}';
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
                    let msg = id ? "Ganti Nama berhasil diupdate!" :
                        "Ganti Nama berhasil ditambahkan!";
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
                text: 'Ganti Nama ini akan dibatalkan!',
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
                                toastr.success("Ganti Nama telah dibatalkan!",
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
