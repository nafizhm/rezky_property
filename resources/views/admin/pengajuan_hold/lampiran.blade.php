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
                                    <h3 class="font-weight-bold text-lg">Form Lampiran Booking</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formData" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Foto Pemohon</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="foto_pemohon" id="foto_pemohon"
                                                accept=".jpg, .jpeg, .png">
                                            <div id="previewFotoPemohon"
                                                class="img-thumbnail mb-2 mt-2 d-flex align-items-center justify-content-center"
                                                style="max-width: 140px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                @if ($data->foto_pemohon)
                                                    <img src="{{ asset('assets/booking/' . $data->foto_pemohon) }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                @else
                                                    <span style="color: #6c757d;">Tidak ada file</span>
                                                @endif
                                            </div>
                                            @if ($data->foto_pemohon)
                                                <button type="button" class="btn btn-danger btn-sm btnHapusFoto"
                                                    data-field="foto_pemohon" data-id="{{ $data->id }}">Hapus
                                                    Foto</button>
                                            @endif
                                        </div>

                                        <label class="col-sm-2 col-form-label">Foto KTP</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="foto_ktp" id="foto_ktp" accept=".jpg, .jpeg, .png">
                                            <div id="previewFotoKtp"
                                                class="img-thumbnail mb-2 mt-2 d-flex align-items-center justify-content-center"
                                                style="max-width: 250px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                @if ($data->foto_ktp)
                                                    <img src="{{ asset('assets/booking/' . $data->foto_ktp) }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                @else
                                                    <span style="color: #6c757d;">Tidak ada file</span>
                                                @endif
                                            </div>
                                            @if ($data->foto_ktp)
                                                <button type="button" class="btn btn-danger btn-sm btnHapusFoto"
                                                    data-field="foto_ktp" data-id="{{ $data->id }}">Hapus Foto</button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Foto NPWP</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="foto_npwp" id="foto_npwp"
                                                accept=".jpg, .jpeg, .png">
                                            <div id="previewFotoNpwp"
                                                class="img-thumbnail mb-2 mt-2 d-flex align-items-center justify-content-center"
                                                style="max-width: 250px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                @if ($data->foto_npwp)
                                                    <img src="{{ asset('assets/booking/' . $data->foto_npwp) }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                @else
                                                    <span style="color: #6c757d;">Tidak ada file</span>
                                                @endif
                                            </div>
                                            @if ($data->foto_npwp)
                                                <button type="button" class="btn btn-danger btn-sm btnHapusFoto"
                                                    data-field="foto_npwp" data-id="{{ $data->id }}">Hapus Foto</button>
                                            @endif
                                        </div>

                                        <label class="col-sm-2 col-form-label">Foto KK</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="foto_kk" id="foto_kk" accept=".jpg, .jpeg, .png">
                                            <div id="previewFotoKk"
                                                class="img-thumbnail mb-2 mt-2 d-flex align-items-center justify-content-center"
                                                style="max-width: 250px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                @if ($data->foto_kk)
                                                    <img src="{{ asset('assets/booking/' . $data->foto_kk) }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                @else
                                                    <span style="color: #6c757d;">Tidak ada file</span>
                                                @endif
                                            </div>
                                            @if ($data->foto_kk)
                                                <button type="button" class="btn btn-danger btn-sm btnHapusFoto"
                                                    data-field="foto_kk" data-id="{{ $data->id }}">Hapus Foto</button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Foto BPJS</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="foto_bpjs" id="foto_bpjs"
                                                accept=".jpg, .jpeg, .png">
                                            <div id="previewFotoBpjs"
                                                class="img-thumbnail mb-2 mt-2 d-flex align-items-center justify-content-center"
                                                style="max-width: 250px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                @if ($data->foto_bpjs)
                                                    <img src="{{ asset('assets/booking/' . $data->foto_bpjs) }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                @else
                                                    <span style="color: #6c757d;">Tidak ada file</span>
                                                @endif
                                            </div>
                                            @if ($data->foto_bpjs)
                                                <button type="button" class="btn btn-danger btn-sm btnHapusFoto"
                                                    data-field="foto_bpjs" data-id="{{ $data->id }}">Hapus Foto</button>
                                            @endif
                                        </div>

                                        <label class="col-sm-2 col-form-label">Foto KTP Pasangan</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="foto_ktp_p" id="foto_ktp_p"
                                                accept=".jpg, .jpeg, .png">
                                            <div id="previewFotoKtpP"
                                                class="img-thumbnail mb-2 mt-2 d-flex align-items-center justify-content-center"
                                                style="max-width: 250px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                @if ($data->foto_ktp_p)
                                                    <img src="{{ asset('assets/booking/' . $data->foto_ktp_p) }}"
                                                        style="max-width: 100%; max-height: 100%;">
                                                @else
                                                    <span style="color: #6c757d;">Tidak ada file</span>
                                                @endif
                                            </div>
                                            @if ($data->foto_ktp_p)
                                                <button type="button" class="btn btn-danger btn-sm btnHapusFoto"
                                                    data-field="foto_ktp_p" data-id="{{ $data->id }}">Hapus
                                                    Foto</button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">File Bukti</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="file_bukti" id="file_bukti"
                                                accept=".jpg, .jpeg, .png, .pdf">
                                            <div id="previewFileBukti"
                                                class="img-thumbnail mb-2 mt-2 d-flex align-items-center justify-content-center"
                                                style="max-width: 140px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                @if ($data->file_bukti)
                                                    @if (Str::endsWith(strtolower($data->file_bukti), ['.jpg', '.jpeg', '.png', '.webp']))
                                                        <img src="{{ asset('assets/booking/' . $data->file_bukti) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <a href="{{ asset('assets/booking/' . $data->file_bukti) }}"
                                                            target="_blank">Lihat File</a>
                                                    @endif
                                                @else
                                                    <span style="color: #6c757d;">Tidak ada file</span>
                                                @endif
                                            </div>
                                            @if ($data->file_bukti)
                                                <button type="button" class="btn btn-danger btn-sm btnHapusFoto"
                                                    data-field="file_bukti" data-id="{{ $data->id }}">Hapus
                                                    File</button>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="modal-footer">
                                        <a href="{{ route('pengajuan-hold.index') }}" class="btn btn-danger">Kembali</a>
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
            function setupImagePreview(inputSelector, previewSelector, defaultText) {
                $(inputSelector).on('change', function() {
                    const file = this.files[0];
                    const previewDiv = $(previewSelector);

                    if (file) {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewDiv.html(
                                    `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`
                                );
                            };
                            reader.readAsDataURL(file);
                        } else if (file.type === 'application/pdf') {
                            previewDiv.html(`<span style="color: #6c757d;">File PDF dipilih</span>`);
                        } else {
                            previewDiv.html(`<span style="color: #6c757d;">${defaultText}</span>`);
                        }
                    } else {
                        previewDiv.html(`<span style="color: #6c757d;">${defaultText}</span>`);
                    }
                });
            }

            setupImagePreview('#foto_pemohon', '#previewFotoPemohon', 'Tidak ada Foto Pemohon');
            setupImagePreview('#foto_ktp', '#previewFotoKtp', 'Tidak ada Foto KTP');
            setupImagePreview('#foto_npwp', '#previewFotoNpwp', 'Tidak ada Foto NPWP');
            setupImagePreview('#foto_kk', '#previewFotoKk', 'Tidak ada Foto KK');
            setupImagePreview('#foto_bpjs', '#previewFotoBpjs', 'Tidak ada Foto BPJS');
            setupImagePreview('#foto_ktp_p', '#previewFotoKtpP', 'Tidak ada Foto KTP Pasangan');
            setupImagePreview('#file_bukti', '#previewFileBukti', 'Tidak ada File Bukti');
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

            let id = '{{ $data->id }}';
            let url = '{{ route('pengajuan-hold.upload', ['id' => ':id']) }}'.replace(':id', id);
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
                    sessionStorage.setItem('success', 'Lampiran berhasil diupdate!');
                    window.location.href = "{{ route('pengajuan-hold.index') }}";
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

        $(document).on('click', '.btnHapusFoto', function(e) {
            e.preventDefault();

            let button = $(this);
            let field = button.data('field');
            let id = button.data('id');
            let previewContainer = button.closest('.col-sm-4').find('div[id^="preview"]');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'File ini akan dihapus secara permanen!',
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
                            '<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Menghapus...';
                        confirmBtn.disabled = true;

                        $.ajax({
                            url: `{{ route('pengajuan-hold.delete-file', ':id') }}`
                                .replace(':id', id),
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                field: field
                            },
                            success: function(response) {
                                if (response.success) {
                                    audio.play();
                                    toastr.success("File telah dihapus!",
                                        "BERHASIL", {
                                            progressBar: true,
                                            timeOut: 3500,
                                            positionClass: "toast-bottom-right"
                                        });
                                    previewContainer.html(
                                        '<span style="color: #6c757d;">Tidak ada file</span>'
                                    );
                                    button.remove();
                                    Swal.close();
                                } else {
                                    audio.play();
                                    toastr.error("Gagal menghapus file.",
                                        "GAGAL!", {
                                            progressBar: true,
                                            timeOut: 3500,
                                            positionClass: "toast-bottom-right"
                                        });
                                    btnText.innerHTML = 'Ya, Hapus';
                                    confirmBtn.disabled = false;
                                }
                            },
                            error: function() {
                                audio.play();
                                toastr.error("Gagal menghapus file.", "GAGAL!", {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: "toast-bottom-right"
                                });
                                btnText.innerHTML = 'Ya, Hapus';
                                confirmBtn.disabled = false;
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush
