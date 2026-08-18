<!-- resources/views/admin/bank/index.blade.php -->
@extends('admin.layout_admin')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-lg">Data Rekap Pembayaran</h3>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <select id="blokFilter" class="form-control select-blok" style="width:200px;">
                                <option value="">Semua Blok</option>
                                @foreach ($bloks as $blok)
                                    <option value="{{ $blok }}">{{ $blok }}</option>
                                @endforeach
                            </select>
                            <select id="statusFilter" class="form-control select-filter" style="width: 200px;">
                                <option value="">Semua Unit</option>
                                <option value="1">Sudah Laku</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="lokasiTabs">
                        @foreach ($lokasi as $index => $item)
                            <li class="nav-item">
                                <a class="nav-link lokasi-tab {{ $index == 0 ? 'active' : '' }}"
                                    data-id="{{ $item->id }}">
                                    {{ $item->nama_kavling }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <table id="data-table" class="table small w-100 table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th rowspan="2" width="5%">No</th>
                                <th colspan="3" class="text-center">Customer</th>
                                <th colspan="3" class="text-center">Dana Masuk</th>
                                <th rowspan="2" width="10%" class="text-center align-middle">Sisa Pembayaran</th>
                                <th rowspan="2" width="10%" class="text-center align-middle">Action</th>
                            </tr>
                            <tr>
                                <th width="12%">Nama Customer</th>
                                <th width="10%">Lokasi Unit</th>
                                <th width="10%">Harga Rumah</th>

                                <th width="10%">Pembayaran</th>
                                <th width="10%">Pencairan</th>
                                <th width="10%">SBUM</th>
                            </tr>
                        </thead>
                    </table>
                    <tbody></tbody>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Tambah Pemasukan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label col-md-3">Tanggal Pembayaran</label>
                            <div class="col-md-4">
                                <input name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control" type="date"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">Nama Customer</label>
                            <div class="col-sm-6">
                                <input name="nasabah_b" id="nasabah_b" class="form-control" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">Lokasi Perumahan</label>
                            <div class="col-md-4">
                                <input name="lokasi_b" id="lokasi_b" class="form-control" type="text" readonly>
                            </div>
                            <div class="col-md-2">
                                <input name="kode_kavling" id="kode_kavling" class="form-control" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">Jenis Pembayaran</label>
                            <div class="col-md-4">
                                <select name="id_kategori_transaksi" id="id_kategori_transaksi"
                                    class="form-control select-jenis-pembayaran">
                                    <option value=""></option>
                                    @foreach ($pembayaran as $bayar)
                                        <option value="{{ $bayar->id }}">{{ $bayar->kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Rekening</label>
                            <div class="col-sm-4">
                                <select name="id_bank" id="id_bank" class="form-control select-rekening">
                                    <option value=""></option>
                                    @foreach ($bankList as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Metode Bayar</label>
                            <div class="col-sm-3">
                                <select name="id_metode_bayar" id="id_metode_bayar"
                                    class="form-control select-metode-bayar">
                                    <option value=""></option>
                                    @foreach ($metodeBayar as $bayar)
                                        <option value="{{ $bayar->id }}">{{ $bayar->jenis_bayar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nominal</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="nominal_bayar" id="nominal_bayar" class="form-control format-number"
                                        type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-7">
                                <textarea name="keterangan_pembayaran" id="keterangan_pembayaran" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bukti Pembayaran</label>
                            <div class="col-sm-4">
                                <input type="file" class="mb-2" id="file" name="file"
                                    accept=".jpg, .jpeg, .png, .pdf">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewLampiran"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada berkas</span>
                                </div>
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
@endsection

@push('scripts')
    <script>
        previewFile('file', 'previewLampiran');

        $(document).ready(function() {
            $('.select-filter').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
            });
            $('.select-blok').select2({
                theme: "bootstrap4",
            });
            $('.select-jenis-pembayaran').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Jenis Pembayaran",
                minimumResultsForSearch: Infinity,
            });
            $('.select-metode-bayar').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Metode Bayar",
                minimumResultsForSearch: -1
            });
            $('.select-rekening').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Rekening",
                minimumResultsForSearch: -1
            });
        });

        let table;
        let currentLokasi = $('.lokasi-tab.active').data('id');
        let statusFilter = '';
        let blokFilter = '';
        $(function() {
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ordering: false,
                pageLength: 10,
                ajax: {
                    url: "{{ route('pembayaran.rekap') }}",
                    data: function(d) {
                        d.lokasi_id = currentLokasi;
                        d.status = statusFilter;
                        d.blok = blokFilter;
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
                        data: 'customer',
                        name: 'customer',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'hrg_jual',
                        name: 'hrg_jual',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pembayaran',
                        name: 'pembayaran',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pencairan',
                        name: 'pencairan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sbum',
                        name: 'sbum',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sisa',
                        name: 'sisa',
                        orderable: false,
                        searchable: false
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
                        width: "2%",
                        targets: 0
                    },
                    {
                        width: "12%",
                        targets: 1
                    },
                    {
                        width: "10%",
                        targets: 2
                    },
                    {
                        width: "10%",
                        targets: 3
                    },
                    {
                        width: "10%",
                        targets: 4
                    },
                    {
                        width: "10%",
                        targets: 5
                    },
                    {
                        width: "10%",
                        targets: 6
                    },
                    {
                        width: "10%",
                        targets: 7
                    },
                    {
                        width: "10%",
                        targets: 8
                    },
                ],
                autoWidth: false
            });
        });

        $(document).on('click', '.lokasi-tab', function() {
            $('.lokasi-tab').removeClass('active');
            $(this).addClass('active');

            currentLokasi = $(this).data('id');
            table.ajax.reload();
        });

        $('#blokFilter').on('change', function() {
            blokFilter = $(this).val();
            table.ajax.reload();
        });

        $('#statusFilter').on('change', function() {
            statusFilter = $(this).val();
            table.ajax.reload();
        });

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(document).on('click', '.bayar-button', function() {
            var id = $(this).data('id');
            var url = `{{ route('ganti-nama.get-customer', ':id') }}`.replace(':id', id);
            $.get(url, function(response) {
                $('#primary_id').val(response.id);
                $('#nasabah_b').val(response.nama_lengkap);
                $('#lokasi_b').val(response.kavling.lokasi.nama_singkat);
                $('#kode_kavling').val(response.kavling.kode_kavling);
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#id_kategori_transaksi').val('').trigger('change');
            $('#id_bank').val('').trigger('change');
            $('#id_metode_bayar').val('').trigger('change');
            $('#primary_id').val('');
            $('#nasabah_b').val('');
            $('#lokasi_b').val('');
            $('#kode_kavling').val('');
            $('#nominal_bayar').val('');
            $('#keterangan_pembayaran').val('');
            $('#file').val('');

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

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = '{{ route('pembayaran.tambah-pemasukan', ['id' => ':id']) }}'.replace(':id',
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
                    $('#modalForm').modal('hide');
                    audio.play();
                    let msg = "Pemasukan berhasil ditambahkan!";
                    toastr.success(msg, "BERHASIL", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });

                    table.ajax.reload(null, false);
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
                    } else {
                        audio.play();
                        toastr.error(
                            "Terjadi kesalahan saat menyimpan data",
                            "Gagal!", {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 3000,
                                positionClass: 'toast-bottom-right'
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
