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
                                    <h3 class="font-weight-bold text-lg">Tambah Barang Masuk</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formData" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal Masuk</label>
                                        <div class="col-sm-3">
                                            <input type="date" id="tanggal" name="tanggal" class="form-control"
                                                value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class="font-weight-bold mb-4">Data PO</h5>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">No. PO</label>
                                        <div class="col-sm-3">
                                            <select class="form-select select-po" name="id_po" id="id_po">
                                                <option value=""></option>
                                                @foreach ($poList as $data)
                                                    <option value="{{ $data->id }}">{{ $data->no_po }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal PO</label>
                                        <div class="col-sm-3">
                                            <input type="date" id="tanggal_po" name="tanggal_po" class="form-control"
                                                disabled>
                                        </div>
                                        <label for="tanggal" class="col-sm-1 col-form-label">Supplier</label>
                                        <div class="col-sm-3">
                                            <input type="text" id="supplier" name="supplier" class="form-control"
                                                disabled>
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
                                        <div class="col-sm-3">
                                            <input type="text" id="nama_penerima" name="nama_penerima"
                                                class="form-control">
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

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

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
                    url: "{{ route('barang-masuk.create') }}",
                    data: function(d) {
                        d.id_po = $('#id_po').val();
                    }
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
                        data: null,
                        render: function(row) {
                            const jumlah = row.jumlah ?? 0;
                            const stok = row.stok ?? 0;
                            return `
                            <input type="hidden" name="stok[]" value="${stok}">
        <input type="text" name="jumlah[]" class="form-control format-number jumlah-input" value="${new Intl.NumberFormat('id-ID').format(jumlah)}" required>
    `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        render: function(row) {
                            const harga_beli = row.harga_beli ?? 0;
                            return `
        <input type="text" name="harga_beli[]" class="form-control format-number" value="${new Intl.NumberFormat('id-ID').format(harga_beli)}" required>
    `;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        render: function() {
                            return `<input type="text" name="keterangan[]" class="form-control">`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#id_po').on('change', function() {
                var id_po = $(this).val();
                if (id_po) {
                    $.ajax({
                        url: `/api/get-po/${id_po}`,
                        type: 'GET',
                        success: function(res) {
                            $('#tanggal_po').val(res.tanggal);
                            $('#supplier').val(res.supplier);
                            $('#keterangan').val(res.keterangan);
                            table.ajax.reload();
                        }
                    });
                } else {
                    $('#tanggal_po, #supplier, #keterangan').val('');
                    table.clear().draw();
                }
            });

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

        $(document).on('input', '.jumlah-input', function() {
            let input = $(this);
            let jumlah = parseInt(input.val().replace(/\D/g, '')) || 0;
            let row = input.closest('tr');
            let id_barang = row.find('input[name="id_barang[]"]').val();
            let id_po = $('#id_po').val();

            $.ajax({
                url: '{{ route('barang-masuk.validate-jumlah') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_barang: id_barang,
                    jumlah: jumlah,
                    id_po: id_po
                },
                success: function(res) {
                    input.removeClass('is-invalid');
                    input.parent().find('.invalid-feedback').remove();

                    if (res.valid === false) {
                        audio.play();
                        input.addClass('is-invalid');
                        input.parent().append(
                            '<span class="invalid-feedback" role="alert"><strong>' + res.message +
                            '</strong></span>'
                        );
                    }

                    toggleSubmitButton();
                },
                error: function(xhr) {
                    input.addClass('is-invalid');
                    input.parent().find('.invalid-feedback').remove();

                    let msg = 'Jumlah tidak valid';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            let json = JSON.parse(xhr.responseText);
                            if (json.message) {
                                msg = json.message;
                            }
                        } catch (e) {
                            msg = 'Terjadi kesalahan';
                        }
                    }

                    input.parent().append(
                        '<span class="invalid-feedback" role="alert"><strong>' + msg +
                        '</strong></span>'
                    );

                    toggleSubmitButton();
                }
            });
        });

        function toggleSubmitButton() {
            let hasError = $('.is-invalid').length > 0;
            $('#submitBtn').prop('disabled', hasError);
        }

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let url = '{{ route('barang-masuk.store') }}';
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
                success: function() {
                    sessionStorage.setItem('success', 'Data berhasil ditambahkan!');
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
