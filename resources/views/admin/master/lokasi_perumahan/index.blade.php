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
                                    <h3 class="font-weight-bold text-lg">Data Lokasi Perumahan</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($permissions['tambah'])
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modalForm">
                                                <i class="fas fa-plus"></i> Tambah Lokasi Perumahan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered w-100 table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Perumahan</th>
                                            <th width="25%">Alamat</th>
                                            <th width="15%">Jumlah Kavling</th>
                                            <th width="20%">Action</th>
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

    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false" aria-labelledby="modalFormLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Form Lokasi Perumahan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formData">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="primary_id" id="primary_id">

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-5">
                                <input type="text" name="nama_kavling" id="nama_kavling" class="form-control">
                            </div>
                            <label class="col-sm-2 col-form-label">Singkatan</label>
                            <div class="col-sm-3">
                                <input type="text" name="nama_singkat" id="nama_singkat" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Header</label>
                            <div class="col-sm-5">
                                <input type="text" name="header" id="header" class="form-control">
                            </div>
                            <label for="stt_tampil" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-3">
                                <select name="stt_tampil" id="stt_tampil" class="form-control select-status">
                                    <option value=""></option>
                                    <option value="1">Penjualan</option>
                                    <option value="2">Proyek</option>
                                    <option value="3">Keduanya</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-8">
                                <textarea name="alamat" id="alamat" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Perusahaan</label>
                            <div class="col-sm-8">
                                <select name="id_perusahaan[]" id="id_perusahaan" class="form-select select-perusahaan"
                                    multiple="multiple" data-placeholder="Pilih Perusahaan">
                                    <option value=""></option>
                                    @foreach ($perusahaanList as $perusahaan)
                                        <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="is_cluster" class="col-sm-2 col-form-label">Cluster</label>
                            <div class="col-sm-4">
                                <select name="is_cluster" id="is_cluster" class="form-control select-cluster">
                                    <option value=""></option>
                                    <option value="1">Ya (Cluster)</option>
                                    <option value="0">Tidak (Non-Cluster)</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Urutan Lokasi</label>
                            <div class="col-sm-3">
                                <input type="number" name="urutan" id="urutan" class="form-control">
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold">Format Penomoran</h6>
                        <small class="text-danger d-block mb-3">
                            - 0000 : nomor urut 4 digit<br>
                            - MM : bulan Romawi<br>
                            - YYYY : tahun berjalan<br>
                            - Reset : reset nomor urut per tahun
                        </small>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">No. Kwitansi</label>
                            <div class="col-sm-4">
                                <input type="text" name="no_kwitansi" id="no_kwitansi" class="form-control"
                                    placeholder="0000/KWITANSI/MM/YYYY">
                            </div>

                            <label class="col-sm-2 col-form-label">No. BAST</label>
                            <div class="col-sm-4">
                                <input type="text" name="no_bast" id="no_bast" class="form-control"
                                    placeholder="0000/BAST/MM/YYYY">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">No. PPJB</label>
                            <div class="col-sm-4">
                                <input type="text" name="no_ppjb" id="no_ppjb" class="form-control"
                                    placeholder="0000/PPJB/MM/YYYY">
                            </div>
                            <label class="col-sm-2 col-form-label">Reset</label>
                            <div class="col-sm-4">
                                <select name="reset_nomor" id="reset_nomor" class="form-control select-reset">
                                    <option value=""></option>
                                    <option value="1">Ya</option>
                                    <option value="2">Tidak</option>
                                </select>
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
@endsection

@push('scripts')
    <script>
        const permissions = @json($permissions);
        const showActionColumn = permissions['edit'] == 1 || permissions['hapus'] == 1;

        $(document).ready(function() {
            $('.select-status').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Status',
                minimumResultsForSearch: Infinity
            });
            $('.select-reset').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih',
                minimumResultsForSearch: Infinity
            });

            $('.select-perusahaan').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Perusahaan",
            });

            $('.select-cluster').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Cluster',
                minimumResultsForSearch: Infinity
            });
        });

        $(function() {
            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: "{{ route('lokasi-kavling.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nama_kavling',
                        name: 'nama_kavling',
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'jumlah_kavling',
                        name: 'jumlah_kavling',
                        className: 'text-center'
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
        });

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.success) {

                    $('#primary_id').val(response.data.id);
                    $('#nama_kavling').val(response.data.nama_kavling);
                    $('#nama_singkat').val(response.data.nama_singkat);
                    $('#header').val(response.data.header);
                    $('#alamat').val(response.data.alamat);
                    $('#urutan').val(response.data.urutan);
                    $('#no_kwitansi').val(response.data.no_kwitansi);
                    $('#no_bast').val(response.data.no_bast);
                    $('#no_ppjb').val(response.data.no_ppjb);
                    $('#reset_nomor').val(response.data.reset_nomor).trigger('change');
                    $('#stt_tampil').val(response.data.stt_tampil).trigger('change');
                    $('#is_cluster').val(response.data.is_cluster).trigger('change');

                    if (response.data.perusahaan) {
                        let perusahaanIds = response.data.perusahaan.map(p => p.id_perusahaan);
                        $('#id_perusahaan').val(perusahaanIds).trigger('change');
                    }

                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $('#primary_id').val('');
            $('#stt_tampil').val('').trigger('change');
            $('#is_cluster').val('').trigger('change');
            $('#reset_nomor').val('').trigger('change');

            $('#id_perusahaan').val(null).trigger('change.select2');

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
            let url = id ? '{{ route('lokasi-kavling.update', ['lokasi_kavling' => ':id']) }}'.replace(':id', id) :
                '{{ route('lokasi-kavling.store') }}';
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
                    let msg = id ? "Lokasi Kavling berhasil diupdate!" :
                        "Lokasi Kavling berhasil ditambahkan!";
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
                text: 'Lokasi Kavling ini akan dihapus secara permanen!',
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
                                toastr.success("Lokasi Kavling telah dihapus!",
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
