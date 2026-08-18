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
                                    <h3 class="font-weight-bold text-lg">Tambah Barang Keluar</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formData" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                                        <div class="col-sm-3">
                                            <input type="date" id="tanggal" name="tanggal" class="form-control"
                                                value="<?= date('Y-m-d') ?>">
                                        </div>
                                        <label for="lampiran" class="col-sm-2 col-form-label">Lampiran</label>
                                        <div class="col-sm-3">
                                            <input type="file" class="mb-2" id="lampiran" name="lampiran"
                                                accept=".jpg, .jpeg, .png, .pdf">
                                            <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                                id="previewLampiran"
                                                style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                <span style="color: #6c757d;">Tidak ada berkas</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-4">
                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <hr>
                                    <h5 class="font-weight-bold mb-4">Daftar Barang</h5>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-sm-10">
                                            <div id="barang-container">
                                                <!-- Baris pertama -->
                                                <div class="barang-row">
                                                    <div class="row mb-3 align-items-center">
                                                        <div class="col-sm-5">
                                                            <select class="form-select select-barang" name="barang[]">
                                                                <option value=""></option>
                                                                @foreach ($barangList as $data)
                                                                    <option value="{{ $data->id }}">{{ $data->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="number" class="form-control" name="jumlah[]"
                                                                placeholder="Jumlah">
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <button type="button" class="btn btn-danger hapus-baris">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tombol tambah dan total harga -->
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-sm-7">
                                                    <button type="button" class="btn btn-primary tambah-baris">
                                                        <i class="fas fa-plus mr-1"></i>Tambah Barang
                                                    </button>
                                                </div>
                                            </div>
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

    <style>
        /* Mengatur lebar maksimum untuk input */
        #barang-container .form-control {
            max-width: 100%;
        }

        /* Mengatur jarak antar baris */
        .barang-row+.barang-row {
            margin-top: 15px;
            /* Atur jarak antar baris */
        }

        /* Mengatur ukuran tombol "Hapus Baris" */
        .hapus-baris {
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
        previewFile('lampiran', 'previewLampiran');

        $(document).ready(function() {
            $('.select-barang').select2({
                theme: "bootstrap4",
                width: '100%',
                placeholder: "Pilih Barang",
            });
        });

        $(document).on('input', 'input[name="jumlah[]"]', function() {
            let input = $(this);
            let jumlah = parseInt(input.val().replace(/\D/g, '')) || 0;
            let row = input.closest('.barang-row');
            let barang = row.find('select[name="barang[]"]').val();

            if (!barang) {
                input.addClass('is-invalid');
                input.parent().find('.invalid-feedback').remove();
                input.parent().append(
                    '<span class="invalid-feedback" role="alert"><strong>Barang harus dipilih terlebih dahulu</strong></span>'
                );
                return;
            }

            $.ajax({
                url: '{{ route('barang-keluar.validate-jumlah') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    barang: barang,
                    jumlah: jumlah,
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

            let url = '{{ route('barang-keluar.store') }}';

            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    sessionStorage.setItem('success', 'Barang Keluar berhasil disimpan!');
                    window.location.href = "{{ route('barang-keluar.index') }}";
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

        // Tambah baris (format ulang input baru)
        $('.tambah-baris').on('click', function() {
            let newRow = `
       <div class="barang-row">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-5">
                    <select class="form-select select-barang" name="barang[]" >
                        <option value=""></option>
                        @foreach ($barangList as $data)
                            <option value="{{ $data->id }}">{{ $data->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="number" class="form-control" name="jumlah[]"
                        placeholder="Jumlah" >
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-danger hapus-baris">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
        `;

            $('#barang-container').append(newRow);
            $('.barang-row:last .select-barang').select2({
                theme: "bootstrap4",
                width: '100%',
                placeholder: 'Pilih Barang',
            });
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('.barang-row').remove();
        });

        $(document).on('input', '.rupiah', function() {
            let value = $(this).val().replace(/\D/g, '');
            $(this).val(value ? formatRupiah(value) : '');
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
    </script>
@endpush
