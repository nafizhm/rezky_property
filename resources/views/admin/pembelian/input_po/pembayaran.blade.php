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
                                    <h3 class="font-weight-bold text-lg">Form Pembayaran PO</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formData" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="primary_id" name="primary_id" value="{{ $dataPO->id }}">
                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-3">
                                            <input type="date" id="tanggal" name="tanggal" class="form-control"
                                                value="{{ $dataPO->tanggal }}" readonly>
                                        </div>
                                        <label for="no_po" class="col-sm-2 col-form-label">No. PO</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="no_po" name="no_po"
                                                value="{{ $dataPO->no_po }}" readonly>
                                        </div>
                                    </div>

                                    <hr>
                                    <h5 class="font-weight-bold mb-4">Pembayaran</h5>

                                    <div class="row mb-4 mt-3">
                                        <label for="id_supplier" class="col-sm-2 col-form-label">Rekening</label>
                                        <div class="col-sm-3">
                                            <select class="form-select select-bank" name="id_bank" id="id_bank">
                                                <option value=""></option>
                                                @foreach ($bankList as $data)
                                                    <option value="{{ $data->id }}"
                                                        {{ $data->id == $dataPO->id_bank ? 'selected' : '' }}>
                                                        {{ $data->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label for="barang" class="col-sm-2 col-form-label">Total Harga</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input type="text" class="form-control rupiah" id="total_harga"
                                                    name="total_harga"
                                                    value="{{ number_format($dataPO->total_harga, 0, ',', '.') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="pembayaran-wrapper">
                                        @foreach ($dataPOP as $item)
                                            <div class="pembayaran-item">
                                                <hr>
                                                <div class="row mb-3">
                                                    <label class="col-sm-2 col-form-label">Tanggal Pembayaran</label>
                                                    <div class="col-sm-2">
                                                        <input type="date" class="form-control"
                                                            name="tanggal_pembayaran[]" value="{{ $item->tanggal }}">
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Terbayar</label>
                                                    <div class="col-sm-2">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp.</span>
                                                            </div>
                                                            <input type="text" class="form-control rupiah"
                                                                name="terbayar[]"
                                                                value="{{ number_format($item->terbayar, 0, ',', '.') }}">
                                                        </div>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Lampiran</label>
                                                    <div class="col-sm-3">
                                                        <input type="file" class="mb-1" name="lampiran[]"
                                                            accept=".jpg,.jpeg,.png,.pdf">

                                                        @if (!empty($item->lampiran))
                                                            <input type="hidden" name="lampiran_lama[]"
                                                                value="{{ $item->lampiran }}">
                                                            <small class="text-primary lihat-lampiran"
                                                                data-src="{{ asset('assets/po/pembayaran/' . $item->lampiran) }}"
                                                                style="cursor:pointer;" data-title="Lampiran Pembayaran">
                                                                Lihat Lampiran
                                                            </small>
                                                        @else
                                                            <input type="hidden" name="lampiran_lama[]" value="">
                                                        @endif
                                                    </div>
                                                    <div class="col-xs-1">
                                                        <button type="button" class="btn btn-danger hapus-pembayaran">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-sm-7 offset-sm-2">
                                    <button type="button" class="btn btn-primary tambah-pembayaran">
                                        <i class="fas fa-plus mr-1"></i>Tambah Pembayaran
                                    </button>
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

    <div class="modal fade" id="modalLampiran" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalLampiranLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title" id="modalLampiranLabel">Lampiran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" id="modalLampiranContent">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #pembayaran-container .form-control {
            max-width: 100%;
        }

        /* Mengatur jarak antar baris */
        .pembayaran-row+.pembayaran-row {
            margin-top: 15px;
            /* Atur jarak antar baris */
        }

        /* Mengatur ukuran tombol "Hapus Baris" */
        .hapus-pembayaran {
            width: 100%;
            /* Pastikan tombol sesuai dengan kolom */
            text-align: center;
        }

        /* Pastikan modal body dan konten tidak memangkas dropdown */
        .modal-body {
            overflow: visible !important;
        }

        /* Untuk modal dengan scroll bawaan Bootstrap atau template */
        .modal-dialog-scrollable .modal-content {
            overflow: visible !important;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.lihat-lampiran', function() {
            let src = $(this).data('src');
            let title = $(this).data('title') || 'Lampiran';

            $('#modalLampiranLabel').text(title);

            let ext = src.split('.').pop().toLowerCase();

            let content = '';
            if (ext === 'pdf') {
                content = `<iframe src="${src}" width="100%" height="600px"></iframe>`;
            } else {
                content = `<img src="${src}" alt="Lampiran" class="img-fluid rounded shadow">`;
            }

            $('#modalLampiranContent').html(content);
            $('#modalLampiran').modal('show');
        });

        $(document).ready(function() {

            $('.select-bank').select2({
                theme: "bootstrap4",
                width: '100%',
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Rekening",
            });

        });

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = '{{ route('input-po.pembayaran', ['id' => ':id']) }}'.replace(':id', id);
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
                    sessionStorage.setItem('success', 'Pembayaran berhasil diupdate!');
                    window.location.href = "{{ route('input-po.index') }}";
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

                        spinner.addClass('d-none');
                        btnText.text('Simpan');
                        submitBtn.prop('disabled', false);
                    }
                }
            });
        });

        function formatNumber(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function unformatNumber(value) {
            return parseInt(value.replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
        }

        function hitungSubtotalDanTotal() {
            let total = 0;
            $('.barang-row').each(function() {
                let jumlah = unformatNumber($(this).find('input[name="jumlah[]"]').val());
                let harga = unformatNumber($(this).find('input[name="harga_beli[]"]').val());
                let subtotal = jumlah * harga;

                $(this).find('input[name="subtotal[]"]').val(formatNumber(subtotal));
                $(this).find('input[name="subtotal2[]"]').val(formatNumber(subtotal));
                total += subtotal;
            });

            $('#total_harga').val(formatNumber(total));
            hitungSisaBayar();
        }

        function hitungSisaBayar() {
            let total = unformatNumber($('#total_harga').val());
            let totalTerbayar = 0;

            $('input[name="terbayar[]"]').each(function() {
                totalTerbayar += unformatNumber($(this).val());
            });

            let sisa = total - totalTerbayar;
            $('#sisa_bayar').val(formatNumber(sisa < 0 ? 0 : sisa));
        }

        $(document).on('input', '#total_harga', function() {
            let rawVal = unformatNumber($(this).val());
            $(this).val(formatNumber(rawVal));
            hitungSisaBayar();
        });

        // Format dan hitung saat input
        $(document).on('input', 'input[name="jumlah[]"], input[name="harga_beli[]"]', function() {
            let input = $(this);
            let rawVal = unformatNumber(input.val());
            input.val(formatNumber(rawVal));
            hitungSubtotalDanTotal();
        });


        $(document).on('click', '.hapus-pembayaran', function() {
            $(this).closest('.pembayaran-row').remove();
            hitungSisaBayar();
        });

        $(document).on('input', 'input[name="terbayar[]"]', function() {
            let rawVal = unformatNumber($(this).val());
            $(this).val(formatNumber(rawVal));
            hitungSisaBayar();
        });

        document.querySelectorAll('.rupiah').forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value) {
                    this.value = formatRupiah(value);
                } else {
                    this.value = '';
                }
            });
        });

        function formatRupiah(angka) {
            let number_string = angka.replace(/\D/g, ''),
                split = number_string.split(''),
                sisa = split.length % 3,
                rupiah = split.slice(0, sisa).join(''),
                ribuan = split.slice(sisa).join('').match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return rupiah;
        }

        $(document).on('click', '.tambah-pembayaran', function() {

            let html = `
    <div class="pembayaran-item">
        <hr>
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Tanggal Pembayaran</label>
            <div class="col-sm-2">
                <input type="date" class="form-control"
                    name="tanggal_pembayaran[]" value="<?= date('Y-m-d') ?>">
            </div>
                <label class="col-sm-1 col-form-label">Terbayar</label>
            <div class="col-sm-2">
                 <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                    <input type="text" class="form-control rupiah"
                        name="terbayar[]">
                </div>
            </div>
            <label class="col-sm-1 col-form-label">Lampiran</label>
            <div class="col-sm-3">
                <input type="file" class="mb-1" name="lampiran[]"
                    accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <div class="col-xs-1">
                <button type="button" class="btn btn-danger hapus-pembayaran">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    `;

            $('#pembayaran-wrapper').append(html);
        });

        $(document).on('click', '.hapus-pembayaran', function() {
            $(this).closest('.pembayaran-item').remove();
        });
    </script>
@endpush
