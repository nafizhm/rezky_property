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
                            <div class="card-header p-3 bg-indigo text-white">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Edit Barang Masuk</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formData" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="primary_id" name="primary_id" value="{{ $data->id }}">

                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal Masuk</label>
                                        <div class="col-sm-4">
                                            <input type="date" id="tanggal" name="tanggal" class="form-control"
                                                value="{{ $data->tanggal }}">
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class="font-weight-bold mb-4">Data PO</h5>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">PO</label>
                                        <div class="col-sm-3">
                                            <input type="text" id="id_po" name="id_po" class="form-control"
                                                disabled value="{{ $data_po->no_po }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal PO</label>
                                        <div class="col-sm-3">
                                            <input type="date" id="tanggal_po" name="tanggal_po" class="form-control"
                                                disabled value="{{ $data_po->tanggal }}">
                                        </div>
                                        <label for="tanggal" class="col-sm-1 col-form-label">Supplier</label>
                                        <div class="col-sm-3">
                                            <input type="text" id="supplier" name="supplier" class="form-control"
                                                disabled value="{{ $supplier }}">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" disabled></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="nama_penerima" class="col-sm-2 col-form-label">Nama Penerima</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="nama_penerima" name="nama_penerima"
                                                class="form-control" value="{{ $data->nama_penerima }}">
                                        </div>
                                    </div>

                                    <hr>
                                    <h5 class="font-weight-bold mb-4">Daftar Barang</h5>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <table class="table data-table table-striped table-bordered w-100">
                                                <thead>
                                                    <tr>
                                                        <th width="50px">No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah</th>
                                                        <th>Harga Beli</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
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
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(document).ready(function() {
            $('.select-barang').select2({
                theme: "bootstrap4",
                width: '100%',
                placeholder: "Pilih Barang",
            });
            $('.select-po').select2({
                theme: "bootstrap4",
                width: '100%',
                placeholder: "Pilih No PO",
            });
        });

        const idPenerimaan = {{ $data->id }};

        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: false,
                ordering: false,
                responsive: true,
                searching: false,
                paging: false,
                info: false,
                lengthChange: false,
                ajax: {
                    url: "{{ route('barang-masuk.show', ':id') }}".replace(':id', idPenerimaan)
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id_barang',
                        name: 'id_barang',
                        render: function(data, type, row) {
                            return `<input type="hidden" name="id_barang[]" value="${data}">${row.id_barang_nama}`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jumlah',
                        render: function(data, type, row) {
                            let formatted = new Intl.NumberFormat('id-ID').format(data || 0);
                            let stok = row.stok || 0;
                            return `
                <input type="hidden" name="stok[]" value="${stok}">
                <input type="text" name="jumlah[]" class="form-control format-number" required value="${formatted}">
            `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'harga_beli',
                        render: function(data) {
                            let formatted = new Intl.NumberFormat('id-ID').format(data || 0);
                            return `<input type="text" name="harga_beli[]" class="form-control format-number" required value="${formatted}">`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'keterangan',
                        render: function(data) {
                            return `<input type="text" name="keterangan[]" class="form-control" required value="${data}">`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Tetap format saat user input
            $(document).on('input', '.format-number', function() {
                let value = $(this).val().replace(/[^\d]/g, '');
                value = new Intl.NumberFormat('id-ID').format(value);
                $(this).val(value);
            });

            $(document).on('keypress', '.format-number', function(e) {
                if (e.which < 48 || e.which > 57) {
                    e.preventDefault();
                }
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
            let url = '{{ route('barang-masuk.update', ['barang_masuk' => ':id']) }}'.replace(':id', id);
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
                success: function() {
                    sessionStorage.setItem('success', 'Data berhasil diupdate!');
                    window.location.href = "{{ route('barang-masuk.index') }}";
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
    </script>
@endpush
