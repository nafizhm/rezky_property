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
                                    <h3 class="font-weight-bold text-lg">Data SPPR</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($permissions['tambah'])
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modalForm">
                                                <i class="fas fa-plus"></i> Tambah SPPR
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="50px">No</th>
                                            <th>Nama</th>
                                            <th>Blok / No</th>
                                            <th>Total</th>
                                            <th>Cicilan/bln</th>
                                            <th width="150px">Action</th>
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

        <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false"
            aria-labelledby="modalFormLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-indigo">
                        <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel"></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formData">
                        @csrf
                        <input type="hidden" id="primary_id" name="primary_id">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="id_customer" class="col-sm-3 col-form-label">Customer</label>
                                <div class="col-sm-8">
                                    <select name="id_customer" id="id_customer" class="form-control select-customer">
                                        <option value=""></option>
                                        @foreach ($customerList as $c)
                                            <option value="{{ $c->id }}">{{ $c->nama_lengkap }} ({{ $c->nik }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <label for="no_sppr" class="col-sm-3 col-form-label">No. SPPR</label>
                                <div class="col-sm-8">
                                    <input type="text" name="no_sppr" id="no_sppr" class="form-control" readonly>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-8">
                                    <input type="text" name="nama" id="nama" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                <div class="col-sm-8">
                                    <textarea name="alamat" id="alamat" class="form-control" readonly rows="2"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nik" id="nik" class="form-control" readonly>
                                </div>
                                <label for="no_telp" class="col-sm-1 col-form-label">Telp</label>
                                <div class="col-sm-3">
                                    <input type="text" name="no_telp" id="no_telp" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="agama" class="col-sm-3 col-form-label">Agama</label>
                                <div class="col-sm-3">
                                    <input type="text" name="agama" id="agama" class="form-control">
                                </div>
                                <label for="pekerjaan" class="col-sm-2 col-form-label">Pekerjaan</label>
                                <div class="col-sm-3">
                                    <input type="text" name="pekerjaan" id="pekerjaan" class="form-control">
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <label for="luas_bangunan" class="col-sm-3 col-form-label">Luas Bangunan</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="luas_bangunan" id="luas_bangunan" class="form-control" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">m&sup2;</span>
                                        </div>
                                    </div>
                                </div>
                                <label for="luas_tanah" class="col-sm-2 col-form-label">Luas Tanah</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" name="luas_tanah" id="luas_tanah" class="form-control" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">m&sup2;</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="blok" class="col-sm-3 col-form-label">Blok</label>
                                <div class="col-sm-2">
                                    <input type="text" name="blok" id="blok" class="form-control" readonly>
                                </div>
                                <label for="no" class="col-sm-2 col-form-label">No</label>
                                <div class="col-sm-2">
                                    <input type="text" name="no" id="no" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="promo" class="col-sm-3 col-form-label">Promo</label>
                                <div class="col-sm-8">
                                    <input type="text" name="promo" id="promo" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="perubahan_posisi" class="col-sm-3 col-form-label">Perubahan Posisi</label>
                                <div class="col-sm-8">
                                    <textarea name="perubahan_posisi" id="perubahan_posisi" class="form-control" rows="2"></textarea>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <label for="harga_jual" class="col-sm-3 col-form-label">Harga Jual</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="harga_jual" id="harga_jual" class="form-control rupiah">
                                    </div>
                                </div>
                                <label for="asumsi_plafon_kpr" class="col-sm-3 col-form-label">Asumsi Plafon KPR</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="asumsi_plafon_kpr" id="asumsi_plafon_kpr" class="form-control rupiah">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="biaya_surat_surat" class="col-sm-3 col-form-label">Biaya Surat-surat</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="biaya_surat_surat" id="biaya_surat_surat" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-6"></div>
                            </div>

                            <div class="form-group row">
                                <label for="biaya_kelebihan_tanah" class="col-sm-3 col-form-label">Biaya Kelebihan Tanah <small class="text-muted">(opsional)</small></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="biaya_kelebihan_tanah" id="biaya_kelebihan_tanah" class="form-control rupiah">
                                    </div>
                                </div>
                                <label for="biaya_sudut" class="col-sm-3 col-form-label">Biaya Sudut <small class="text-muted">(opsional)</small></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="biaya_sudut" id="biaya_sudut" class="form-control rupiah">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="biaya_lain_lain" class="col-sm-3 col-form-label">Biaya Lain-lain <small class="text-muted">(opsional)</small></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="biaya_lain_lain" id="biaya_lain_lain" class="form-control rupiah">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cicilan_per_bulan" class="col-sm-3 col-form-label">Cicilan per Bulan</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="cicilan_per_bulan" id="cicilan_per_bulan" class="form-control rupiah">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="font-weight-bold mb-3">Rincian Pembayaran</h6>

                            <div class="form-group row">
                                <label for="jumlah_booking_fee" class="col-sm-3 col-form-label">Booking Fee</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="jumlah_booking_fee" id="jumlah_booking_fee" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_booking" id="keterangan_booking" class="form-control" placeholder="Keterangan booking">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nominal_dp" class="col-sm-3 col-form-label">DP</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="nominal_dp" id="nominal_dp" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_dp" id="keterangan_dp" class="form-control" placeholder="Keterangan DP">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nominal_biaya_posisi_unit" class="col-sm-3 col-form-label">Biaya Posisi Unit</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="nominal_biaya_posisi_unit" id="nominal_biaya_posisi_unit" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_posisi_unit" id="keterangan_posisi_unit" class="form-control" placeholder="Keterangan posisi unit">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nominal_biaya_kpr" class="col-sm-3 col-form-label">Biaya KPR</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="nominal_biaya_kpr" id="nominal_biaya_kpr" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_kpr" id="keterangan_kpr" class="form-control" placeholder="Keterangan KPR">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nominal_blokir_angsuran" class="col-sm-3 col-form-label">Blokir Angsuran</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="nominal_blokir_angsuran" id="nominal_blokir_angsuran" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_blokir_angsuran" id="keterangan_blokir_angsuran" class="form-control" placeholder="Keterangan blokir angsuran">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nominal_biaya_materai" class="col-sm-3 col-form-label">Biaya Materai</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="nominal_biaya_materai" id="nominal_biaya_materai" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_materai" id="keterangan_materai" class="form-control" placeholder="Keterangan materai">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="nominal_biaya_buka_tabungan" class="col-sm-3 col-form-label">Biaya Buka Tabungan</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="nominal_biaya_buka_tabungan" id="nominal_biaya_buka_tabungan" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_tabungan" id="keterangan_tabungan" class="form-control" placeholder="Keterangan tabungan">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="peningkatan_mutu" class="col-sm-3 col-form-label">Biaya Peningkatan SHM</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="text" name="peningkatan_mutu" id="peningkatan_mutu" class="form-control rupiah">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="keterangan_shm" id="keterangan_shm" class="form-control" placeholder="Keterangan SHM">
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <label for="id_marketing" class="col-sm-3 col-form-label">Marketing</label>
                                <div class="col-sm-8">
                                    <select name="id_marketing" id="id_marketing" class="form-select select-marketing">
                                        <option value=""></option>
                                        @foreach ($marketingList as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_marketing }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="penandatangan" class="col-sm-3 col-form-label">Penandatangan</label>
                                <div class="col-sm-8">
                                    <input type="text" name="penandatangan" id="penandatangan" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
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

    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '[data-target="#modalForm"]', function() {
            $('#modalFormLabel').text('Tambah SPPR');
            $('#id_customer').val('').trigger('change').prop('disabled', false);
            $('#no_sppr').val(nextNoSppr);
        });

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');
        var permissions = @json($permissions);
        var nextNoSppr = '{{ $nextNoSppr }}';
        var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

        $(function() {
            $('.select-customer').select2({
                theme: "bootstrap4",
                width: '100%',
                placeholder: "Pilih Customer",
                // dropdownParent: $('#modalForm'),
            });

            $('.select-marketing').select2({
                theme: "bootstrap4",
                width: '100%',
                placeholder: "Pilih Marketing",
                // dropdownParent: $('#modalForm'),
            });

            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: "{{ route('sppr.index') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'customer_nama',
                    name: 'customer_nama',
                    orderable: false,
                    searchable: true
                }, {
                    data: 'customer_lokasi',
                    name: 'customer_lokasi',
                    orderable: false,
                    searchable: true
                }, {
                    data: 'total_format',
                    name: 'total_format',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'cicilan_format',
                    name: 'cicilan_format',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    visible: showActionColumn,
                    className: 'text-center'
                }],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }]
            });
        });

        $(document).on('change', '#id_customer', function() {
            let id = $(this).val();
            if (!id) {
                $('#nama, #alamat, #nik, #no_telp, #luas_bangunan, #luas_tanah, #blok, #no, #harga_jual, #biaya_surat_surat, #peningkatan_mutu').val('');
                $('#total_yang_harus_dibayar, #cicilan_per_bulan, #asumsi_plafon_kpr, #biaya_kelebihan_tanah, #biaya_sudut, #biaya_lain_lain').val('');
                $('#agama, #pekerjaan').val('');
                $('#id_marketing').val('').trigger('change');
                return;
            }

            const url = '{{ route('sppr.get-customer-detail', ':id') }}'.replace(':id', id);
            $.get(url, function(res) {
                if (res.status === 'success') {
                    let d = res.data;
                    $('#nama').val(d.nama_lengkap || '');
                    $('#alamat').val(d.alamat || '');
                    $('#nik').val(d.nik || '');
                    $('#no_telp').val(d.no_telp || '');
                    $('#luas_bangunan').val(d.luas_bangunan || 0);
                    $('#luas_tanah').val(d.luas_tanah || 0);
                    $('#blok').val(d.blok || '');
                    $('#no').val(d.no || '');
                    $('#harga_jual').val(formatNumber(d.harga_jual) || '');
                    $('#biaya_surat_surat').val(formatNumber(d.biaya_surat_surat) || '');
                    $('#peningkatan_mutu').val(formatNumber(d.peningkatan_mutu) || '');
                    $('#jumlah_booking_fee').val(formatNumber(d.jumlah_booking_fee) || '');
                    $('#pekerjaan').val(d.pekerjaan || '');
                    $('#agama').val(d.agama || '');
                    if (!$('#primary_id').val()) {
                        if (d.id_marketing) {
                            $('#id_marketing').val(d.id_marketing).trigger('change');
                        } else {
                            $('#id_marketing').val('').trigger('change');
                        }
                    }
                    hitungTotal();
                }
            });
        });

        $(document).on('input', '.rupiah', function() {
            let value = $(this).val().replace(/\D/g, '');
            $(this).val(value ? formatRupiah(value) : '');
            hitungTotal();
        });

        function hitungTotal() {
            let hargaJual = unformatNumber($('#harga_jual').val());
            let asumsiPlafon = unformatNumber($('#asumsi_plafon_kpr').val());
            let peningkatanMutu = unformatNumber($('#peningkatan_mutu').val());
            let total = hargaJual - (asumsiPlafon + peningkatanMutu);
            $('#total_yang_harus_dibayar').val(formatNumber(total));
        }

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

        function formatNumber(num) {
            if (!num && num !== 0) return '';
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function unformatNumber(str) {
            if (!str) return 0;
            return parseInt(str.replace(/\./g, '')) || 0;
        }

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#modalFormLabel').text('Edit SPPR');
                    let d = response.data;
                    $('#primary_id').val(d.id);
                    $('#id_customer').val(d.id_customer).prop('disabled', true);
                    $('#no_sppr').val(d.no_sppr || '');
                    $('#nama').val(d.nama);
                    $('#alamat').val(d.alamat);
                    $('#nik').val(d.nik);
                    $('#no_telp').val(d.no_telp);
                    $('#luas_bangunan').val(d.luas_bangunan);
                    $('#luas_tanah').val(d.luas_tanah);
                    $('#blok').val(d.blok);
                    $('#no').val(d.no);
                    $('#harga_jual').val(formatNumber(d.harga_jual));
                    $('#asumsi_plafon_kpr').val(formatNumber(d.asumsi_plafon_kpr));
                    $('#biaya_surat_surat').val(formatNumber(d.biaya_surat_surat));
                    $('#peningkatan_mutu').val(formatNumber(d.peningkatan_mutu));
                    if (d.biaya_kelebihan_tanah) $('#biaya_kelebihan_tanah').val(formatNumber(d.biaya_kelebihan_tanah));
                    if (d.biaya_sudut) $('#biaya_sudut').val(formatNumber(d.biaya_sudut));
                    if (d.biaya_lain_lain) $('#biaya_lain_lain').val(formatNumber(d.biaya_lain_lain));
                    $('#total_yang_harus_dibayar').val(formatNumber(d.total_yang_harus_dibayar));
                    $('#jumlah_booking_fee').val(formatNumber(d.jumlah_booking_fee));
                    $('#cicilan_per_bulan').val(formatNumber(d.cicilan_per_bulan));
                    $('#agama').val(d.agama || '');
                    $('#pekerjaan').val(d.pekerjaan || '');
                    $('#promo').val(d.promo || '');
                    $('#perubahan_posisi').val(d.perubahan_posisi || '');
                    $('#keterangan_booking').val(d.keterangan_booking || '');
                    if (d.nominal_dp) $('#nominal_dp').val(formatNumber(d.nominal_dp));
                    $('#keterangan_dp').val(d.keterangan_dp || '');
                    if (d.nominal_biaya_posisi_unit) $('#nominal_biaya_posisi_unit').val(formatNumber(d.nominal_biaya_posisi_unit));
                    $('#keterangan_posisi_unit').val(d.keterangan_posisi_unit || '');
                    if (d.nominal_biaya_kpr) $('#nominal_biaya_kpr').val(formatNumber(d.nominal_biaya_kpr));
                    $('#keterangan_kpr').val(d.keterangan_kpr || '');
                    if (d.nominal_blokir_angsuran) $('#nominal_blokir_angsuran').val(formatNumber(d.nominal_blokir_angsuran));
                    $('#keterangan_blokir_angsuran').val(d.keterangan_blokir_angsuran || '');
                    if (d.nominal_biaya_materai) $('#nominal_biaya_materai').val(formatNumber(d.nominal_biaya_materai));
                    $('#keterangan_materai').val(d.keterangan_materai || '');
                    if (d.nominal_biaya_buka_tabungan) $('#nominal_biaya_buka_tabungan').val(formatNumber(d.nominal_biaya_buka_tabungan));
                    $('#keterangan_tabungan').val(d.keterangan_tabungan || '');
                    $('#keterangan_shm').val(d.keterangan_shm || '');
                    if (d.id_marketing) {
                        $('#id_marketing').val(d.id_marketing).trigger('change');
                    } else {
                        $('#id_marketing').val('').trigger('change');
                    }
                    $('#penandatangan').val(d.penandatangan || '');
                    $('#keterangan').val(d.keterangan || '');
                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#primary_id').val('');
            $('#id_customer').val('').trigger('change').prop('disabled', false);
            $('#no_sppr').val('');
            $('#id_marketing').val('').trigger('change');
            $('#penandatangan').val('');
            $('#keterangan').val('');
            $('#agama, #pekerjaan, #promo').val('');
            $('#perubahan_posisi, #keterangan_booking').val('');
            $('#nominal_dp, #keterangan_dp').val('');
            $('#nominal_biaya_posisi_unit, #keterangan_posisi_unit').val('');
            $('#nominal_biaya_kpr, #keterangan_kpr').val('');
            $('#nominal_blokir_angsuran, #keterangan_blokir_angsuran').val('');
            $('#nominal_biaya_materai, #keterangan_materai').val('');
            $('#nominal_biaya_buka_tabungan, #keterangan_tabungan').val('');
            $('#keterangan_shm').val('');

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
            let url = id ? '{{ route('sppr.update', ['sppr' => ':id']) }}'.replace(':id', id) :
                '{{ route('sppr.store') }}';
            let method = id ? 'PUT' : 'POST';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);

            if (id) {
                formData.set('id_customer', $('#id_customer').val());
            }

            let rupiahFields = ['harga_jual', 'asumsi_plafon_kpr', 'biaya_surat_surat', 'peningkatan_mutu',
                'biaya_kelebihan_tanah', 'biaya_sudut', 'biaya_lain_lain', 'total_yang_harus_dibayar',
                'jumlah_booking_fee', 'cicilan_per_bulan', 'nominal_dp', 'nominal_biaya_posisi_unit',
                'nominal_biaya_kpr', 'nominal_blokir_angsuran', 'nominal_biaya_materai', 'nominal_biaya_buka_tabungan'
            ];
            rupiahFields.forEach(function(field) {
                let val = $('#' + field).val();
                formData.set(field, unformatNumber(val));
            });

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
                    let msg = id ? "Data berhasil diupdate!" : "Data berhasil ditambahkan!";
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
                            '<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Menghapus...';
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
                                $('.data-table').DataTable().ajax.reload(null, false);
                                Swal.close();
                            },
                            error: function() {
                                audio.play();
                                toastr.error("Gagal menghapus data.", "GAGAL!", {
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
