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
                            <div class="card-header p-3">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Data Pengajuan Hold</h3>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('pengajuan-hold.arsip') }}" class="btn btn-sm btn-primary"><i
                                                class="fas fa-archive mr-1"></i> Arsip Pengajuan Hold</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped data-table w-100">
                                    <thead>
                                        <tr>
                                            <th width="30px">No</th>
                                            <th>Customer</th>
                                            <th>Marketing</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                            <th class="text-center" width="200px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Edit Customer Booking</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="tgl_booking" name="tgl_booking" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Nama Lengkap <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <input name="nama_lengkap" id="nama_lengkap" class="form-control" type="text">
                            </div>
                            <label class="control-label col-sm-2">NIK <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <input name="nik" id="nik" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Tempat Lahir <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <input name="tempat_lahir" id="tempat_lahir" class="form-control" type="text">
                            </div>
                            <label class="control-label col-sm-2">Tanggal Lahir <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <input name="tgl_lahir" id="tgl_lahir" class="form-control" type="date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">No. Telp / WA <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <input name="no_telp" id="no_telp" class="form-control" type="text">
                            </div>
                            <label class="control-label col-sm-2">Jenis Kelamin <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control select-jk" name="jenis_kelamin" id="jenis_kelamin">
                                    <option value=""></option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Email</label>
                            <div class="col-sm-4">
                                <input name="email" id="email" class="form-control" type="text">
                            </div>
                            <label class="control-label col-sm-2">NPWP</label>
                            <div class="col-sm-3">
                                <input name="npwp" id="npwp" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Pekerjaan</label>
                            <div class="col-sm-4">
                                <input name="pekerjaan" id="pekerjaan" class="form-control" type="text">
                            </div>
                            <label class="control-label col-sm-2">No. BPJS Kes</label>
                            <div class="col-sm-3">
                                <input name="no_bpjs_kes" id="no_bpjs_kes" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Alamat KTP <span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Alamat Domisili <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Status Pernikahan</label>
                            <div class="col-sm-4">
                                <select class="form-control select-status" name="status_pernikahan"
                                    id="status_pernikahan">
                                    <option value=""></option>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Nama Pasangan</label>
                            <div class="col-sm-4">
                                <input name="nama_p" id="nama_p" class="form-control" type="text">
                            </div>
                            <label class="control-label col-sm-2">NIK Pasangan</label>
                            <div class="col-sm-3">
                                <input name="nik_p" id="nik_p" class="form-control" type="text">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Nama Saudara</label>
                            <div class="col-sm-4">
                                <input name="nama_saudara" id="nama_saudara" class="form-control" type="text">
                            </div>
                            <label class="control-label col-sm-2">No. Telp Saudara</label>
                            <div class="col-sm-3">
                                <input name="no_telp_saudara" id="no_telp_saudara" class="form-control" type="text">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Lokasi Perumahan <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control select-lokasi" name="id_lokasi" id="id_lokasi">
                                    <option value=""></option>
                                    @foreach ($lokasi as $l)
                                        <option value="{{ $l->id }}">{{ $l->nama_kavling }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="control-label col-sm-2">Blok/Kav <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <select name="id_kavling" id="id_kavling" class="form-control select-kavling"></select>
                            </div>
                        </div>

                        <div id="rincian-harga-container">
                            <div class="text-muted">Pilih kavling terlebih dahulu untuk menampilkan rincian harga.</div>
                        </div>
                        <input type="hidden" name="total_harga" id="total_harga" value="">

                        <hr>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Marketing <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control select-marketing" name="id_marketing" id="id_marketing">
                                    <option value=""></option>
                                    @foreach ($marketing as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama_marketing }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Jenis Perumahan <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control select-jp" name="jenis_perumahan" id="jenis_perumahan">
                                    <option value=""></option>
                                    <option value="Subsidi">Subsidi</option>
                                    <option value="Komersil">Komersil</option>
                                </select>
                            </div>
                            <label class="control-label col-sm-2">Jenis Pembelian <span
                                    style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control select-pembelian" name="jenis_pembelian"
                                    id="jenis_pembelian">
                                    <option value=""></option>
                                    <option value="Pembelian Cash">Pembelian Cash</option>
                                    <option value="Cash Bertahap">Cash Bertahap</option>
                                    <option value="KPR">KPR</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Booking Fee <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="booking_fee" id="booking_fee" class="form-control format-number"
                                        type="text">
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
        $(document).ready(function() {
            const successMsg = sessionStorage.getItem('success');
            if (successMsg) {
                audio.play();
                toastr.success(successMsg, "BERHASIL", {
                    progressBar: true,
                    timeOut: 3500,
                    positionClass: "toast-bottom-right",
                });
                sessionStorage.removeItem('success');
            }
        });

        $(document).ready(function() {
            $('.select-lokasi').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Lokasi",
            });
            $('.select-jk').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Jenis Kelamin",
            });
            $('.select-kavling').select2({
                theme: "bootstrap4",
            });
            $('.select-marketing').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Marketing",
            });

            $('.select-jp').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Jenis Perumahan",
            });

            $('.select-pembelian').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Jenis Pembelian",
            });

            $('.select-status').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Status",
                minimumResultsForSearch: Infinity,
            });
        });

        function formatRupiah(angka) {
            if (!angka) return '';
            return angka.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $('#status').on('change', function() {
            if ($(this).val() === 'Menikah') {
                $('#pasangan').show();
                $('#nama_p, #nik_p').prop('required', true);
            } else {
                $('#pasangan').hide();
                $('#nama_p, #nik_p').prop('required', false);
            }
        }).trigger('change');

        var permissions = @json($permissions);
        var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

        $(function() {
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ordering: false,
                ajax: "{{ route('pengajuan-hold.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap',
                        searchable: true
                    },
                    {
                        data: 'nama_marketing',
                        name: 'nama_marketing',
                    },
                    {
                        data: 'kode_kavling',
                        name: 'kode_kavling',
                    },

                    {
                        data: 'stt_reg',
                        name: 'stt_reg',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        visible: showActionColumn
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

        let isEditMode = false;

        $(document).on('click', '.edit-button', function() {
            isEditMode = true;

            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    const data = response.data;

                    $('#primary_id').val(data.id);
                    $('#tgl_booking').val(response.data.tgl_booking_formatted);
                    $('#nama_lengkap').val(data.nama_lengkap);
                    $('#nik').val(data.nik);
                    $('#nik_p').val(data.nik_p);
                    $('#tempat_lahir').val(data.tempat_lahir);
                    $('#tgl_lahir').val(data.tgl_lahir);
                    $('#jenis_kelamin').val(data.jenis_kelamin).trigger('change');
                    $('#no_telp').val(data.no_telp);
                    $('#email').val(data.email);
                    $('#npwp').val(data.npwp);
                    $('#no_bpjs_kes').val(data.no_bpjs_kes);
                    $('#booking_fee').val(formatNumber(data.booking_fee));
                    $('#alamat_ktp').val(data.alamat_ktp);
                    $('#alamat_domisili').val(data.alamat_domisili);
                    $('#pekerjaan').val(data.pekerjaan);
                    $('#status_pernikahan').val(data.status_pernikahan).trigger('change');
                    $('#nama_p').val(data.nama_p);
                    $('#nama_saudara').val(data.nama_saudara);
                    $('#no_telp_saudara').val(data.no_telp_saudara);

                    $('#id_lokasi').val(data.id_lokasi).trigger('change');
                    setTimeout(function() {
                        $('#id_kavling').val(data.id_kavling).trigger('change');
                        setTimeout(function() {
                            if (data.rincian_biaya && data.rincian_biaya.length > 0) {
                                renderRincianHarga(data.rincian_biaya, data.total_harga);
                            }
                        }, 300);
                    }, 500);

                    $('#id_marketing').val(data.id_marketing).trigger('change');
                    $('#jenis_pembelian').val(data.jenis_pembelian).trigger('change');
                    $('#jenis_perumahan').val(data.jenis_perumahan).trigger('change');

                    $('#modalForm').modal('show');

                }
            });
        });

        $(document).ready(function() {
            $('.select-lokasi').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Lokasi",
            });

            $('.select-kavling').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Kavling",
            });

            const routeGetKavling = "{{ route('pengajuan-hold.getKavling', ':id') }}";
            const routeGetHarga = "{{ route('pengajuan-hold.getHargaKavling', ':id') }}";

            function renderRincianHarga(rincian, total) {
                let container = $('#rincian-harga-container');
                container.empty();

                if (!rincian || rincian.length === 0) {
                    container.html('<div class="text-muted">Tidak ada rincian biaya.</div>');
                    $('#total_harga').val('');
                    return;
                }

                let html = '';
                rincian.forEach(function(item) {
                    if ((item.nilai || 0) <= 0) return;
                    html += '<div class="form-group row">';
                    html += '<label class="control-label col-sm-3">' + item.nama + '</label>';
                    html += '<div class="col-sm-4">';
                    html += '<div class="input-group">';
                    html += '<div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>';
                    html += '<input type="text" class="form-control" readonly value="' + item.nilai.toLocaleString('id-ID') + '">';
                    html += '</div></div></div>';
                });

                html += '<div class="form-group row">';
                html += '<label class="control-label col-sm-3"><strong>Total Harga</strong></label>';
                html += '<div class="col-sm-4">';
                html += '<div class="input-group">';
                html += '<div class="input-group-prepend"><span class="input-group-text">Rp.</span></div>';
                html += '<input type="text" class="form-control" readonly value="' + (total || 0).toLocaleString('id-ID') + '">';
                html += '</div></div></div>';

                container.html(html);
                $('#total_harga').val(total || 0);
            }

            $('#id_lokasi').on('change', function() {
                let idLokasi = $(this).val();

                if (!isEditMode) {
                    renderRincianHarga([], 0);
                }

                if (idLokasi) {
                    const urlKavling = routeGetKavling.replace(':id', idLokasi);
                    $.get(urlKavling, function(data) {
                        let options = '<option value=""></option>';
                        data.forEach(function(item) {
                            options +=
                                `<option value="${item.id}">${item.kode_kavling}</option>`;
                        });
                        $('#id_kavling').html(options);

                        if (isEditMode) {
                            $('#id_kavling').val($('#id_kavling').data('selected')).trigger(
                                'change.select2');
                        }
                    });
                }
            });
            $('#id_kavling').on('change', function() {
                if (isEditMode) return;

                let idKavling = $(this).val();

                if (idKavling) {
                    const urlHarga = routeGetHarga.replace(':id', idKavling);
                    $.get(urlHarga, function(data) {
                        renderRincianHarga(data.rincian_biaya, data.total_harga);
                    });
                } else {
                    renderRincianHarga([], 0);
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            isEditMode = false;
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('#rincian-harga-container').html('<div class="text-muted">Pilih kavling terlebih dahulu untuk menampilkan rincian harga.</div>');
            $('.jenis_kelamin').val('').trigger('change');
            $('.status').val('').trigger('change');
            $('.id_lokasi').val('').trigger('change');
            $('.id_kavling').val('').trigger('change');
            $('.id_marketing').val('').trigger('change');
            $('.jenis_perumahan').val('').trigger('change');
            $('.jenis_pembelian').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
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
            let url = id ? '{{ route('pengajuan-hold.update', ['pengajuan_hold' => ':id']) }}'.replace(':id',
                    id) :
                '{{ route('pengajuan-hold.store') }}';
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
                    let msg = id ? "Hold berhasil diupdate!" : "Hold berhasil ditambahkan!";
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

        function formatRupiah(angka) {
            if (!angka) return '';
            return angka.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    </script>
@endpush

