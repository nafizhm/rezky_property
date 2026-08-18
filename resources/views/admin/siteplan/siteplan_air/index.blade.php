<!-- resources/views/admin/bank/index.blade.php -->
@extends('admin.layout_admin')
@section('content')
    <style>
        .legend {
            position: fixed;
            top: 80px;
            right: 30px;
            padding: 10px;
            font-size: 14px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.4);
            transition: right 0.3s ease;
            width: 200px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            background-color: #008cff;
            margin-right: 5px;
            border-radius: 50%;
            border: 2px solid #000;
        }

        .legend-color1 {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 50%;
            border: 2px solid #000;
        }

        .toggle-btn {
            width: 100%;
            padding: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-top: 10px;
        }

        .show-btn {
            position: fixed;
            top: 100px;
            right: 30px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.4);
            cursor: pointer;
            display: none;
        }

        .svg-container {
            width: 100%;
            height: 100vh;
            overflow: hidden;
            border: 2px solid #d1cfcf;
            position: relative;
        }

        .svg-container svg {
            width: 100%;
            height: 100%;
            cursor: grab;
            transition: transform 0.1s ease-out;
        }
    </style>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-lg text-dark">Siteplan Air</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">

                        <!-- ================================================================================================== -->

                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    @foreach ($lokasiKavling as $index => $kav)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                                id="custom-tabs-four-{{ $kav->id }}-tab" data-toggle="pill"
                                                href="#custom-tabs-four-{{ $kav->id }}" role="tab">
                                                {{ $kav->nama_kavling }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    @foreach ($lokasiKavling as $index => $kav)
                                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                            id="custom-tabs-four-{{ $kav->id }}" role="tabpanel">

                                            <a href="{{ route('siteplan-air.cetak.pdf', $kav->id) }}" target="_blank"
                                                class="btn btn-danger btn-sm mr-1">
                                                <i class="fas fa-file-pdf mr-1"></i> Cetak Denah PDF
                                            </a>
                                            <a href="{{ route('siteplan-air.cetak.jpg', $kav->id) }}" target="_blank"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-file-image mr-1"></i> Download Denah JPG
                                            </a>

                                            {{-- SVG Container khusus lokasi ini --}}
                                            <div class="svg-container mt-3">
                                                <button class="reset-button btn btn-success btn-sm"
                                                    style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                                    Reset Siteplan
                                                </button>

                                                {{-- SVG Header --}}
                                                @if ($kav->masterSvg)
                                                    {!! str_replace(['[[lebar]]', '[[tinggi]]'], ['100%', '100%'], $kav->masterSvg->header_svg) !!}
                                                @endif

                                                {{-- Loop kavling --}}
                                                @foreach ($kav->kavlingPeta as $pt)
                                                    @php
                                                     $warna = '#ffffff';

                                                    if ($pt->listrikAir) {
                                                        $warna = '#008cff';
                                                    }
                                                    @endphp


                                                    @if ($pt->jenis_map == 'polygon')
                                                        <a href="javascript:void(0);" class="detail-button"
                                                            data-id="{{ $pt->id }}"
                                                            data-url="{{ route('siteplan-air.show', $pt->id) }}">
                                                            {!! str_replace(
                                                                ['[[1]]', '[[2]]', '[[3]]', '[[4]]'],
                                                                [$pt->map, $warna, $pt->matrik, $pt->kode_kavling],
                                                                $kav->masterSvg->polygon_svg,
                                                            ) !!}
                                                        </a>
                                                    @elseif ($pt->jenis_map == 'path')
                                                        <a href="javascript:void(0);" class="detail-button"
                                                            data-id="{{ $pt->id }}"
                                                            data-url="{{ route('siteplan-air.show', $pt->id) }}">
                                                            {!! str_replace(
                                                                ['[[1]]', '[[2]]', '[[3]]', '[[4]]'],
                                                                [$pt->map, $warna, $pt->matrik, $pt->kode_kavling],
                                                                $kav->masterSvg->path_svg,
                                                            ) !!}
                                                        </a>
                                                    @endif
                                                @endforeach

                                                {{-- SVG Footer --}}
                                                @if ($kav->masterSvg)
                                                    {!! $kav->masterSvg->footer_svg !!}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- /.card -->
                        </div>
                    </div>

                    <!-- ========================================================================================== -->

                    <!-- Tombol show di luar legenda -->
                    <button class="show-btn btn-xs" id="show-btn" onclick="toggleLegend()">Show</button>

                    <!-- Tambahkan legenda -->
                    <div class="legend" id="legend">
                            <div class="legend-item">
                                <div class="legend-color"></div>
                                Terpasang
                            </div>
                            <div class="legend-item">
                                <div class="legend-color1"></div>
                                Belum Terpasang
                            </div>

                        <!-- Tombol hide -->
                        <button class="toggle-btn" onclick="toggleLegend()">Hide</button>
                    </div>
                    <!-- ========================================================================================== -->

                </div>
            </div>
        </section>
    </div><!-- /.container-fluid -->
    <!-- /.content-wrapper -->

    <div class="modal fade" id="modalDetail" data-focus="false" tabindex="-1" role="dialog"
        aria-labelledby="modalDetailLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalDetailLabel">Detail Data Kavling</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-101-tab" data-toggle="pill"
                                        href="#custom-tabs-four-101">Data Unit Rumah</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-102-tab" data-toggle="pill"
                                        href="#custom-tabs-four-102">Data Customer</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-104-tab" data-toggle="pill"
                                        href="#custom-tabs-four-104">Tagihan & Pembayaran</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-103-tab" data-toggle="pill"
                                        href="#custom-tabs-four-103">Foto Unit</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-four-101">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Perumahan</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="nama_kavling" id="nama_kavling"
                                                class="form-control" readonly>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Kode Kavling</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="kode_kavling" id="kode_kavling"
                                                class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Panjang Kanan</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="panjang_kanan" id="panjang_kanan"
                                                    class="form-control format-decimal" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Panjang Kiri</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="panjang_kiri" id="panjang_kiri"
                                                    class="form-control format-decimal" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Lebar Depan</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="lebar_depan" id="lebar_depan"
                                                    class="form-control format-decimal" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Lebar Belakang</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="lebar_belakang" id="lebar_belakang"
                                                    class="form-control format-decimal" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Luas Tanah</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="luas_tanah" id="luas_tanah"
                                                    class="form-control format-decimal" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Luas Bangunan</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="luas_bangunan" id="luas_bangunan"
                                                    class="form-control format-decimal" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Harga Jual -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Harga Jual</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input type="text" name="hrg_jual" id="hrg_jual"
                                                    class="form-control format-number" readonly>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Daya Listrik</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="daya_listrik" id="daya_listrik"
                                                class="form-control format-number" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Keterangan</label>
                                        <div class="col-sm-3">
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" readonly></textarea>
                                        </div>
                                        <label class="col-sm-2 col-form-label">No. Sertif</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="no_sertifikat" id="no_sertifikat"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-102">
                                    <!-- Nama Lengkap -->
                                    <div class="form-group row">
                                        <label for="nama_lengkap" class="col-sm-2 col-form-label">Nama
                                            Lengkap</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="nama_lengkap"
                                                name="nama_lengkap" readonly>
                                        </div>
                                    </div>

                                    <!-- No. KTP -->
                                    <div class="form-group row">
                                        <label for="nik" class="col-sm-2 col-form-label">No. KTP</label>
                                        <div class="col-sm-3">
                                            <input name="nik" id="nik" class="form-control" type="text"
                                                readonly>
                                        </div>
                                        <label for="no_telp" class="col-sm-2 col-form-label">No. Telp / WA</label>
                                        <div class="col-sm-3">
                                            <input name="no_telp" id="no_telp" class="form-control" type="text"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Tempat Lahir -->
                                    <div class="form-group row">
                                        <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat
                                            Lahir</label>
                                        <div class="col-sm-3">
                                            <input name="tempat_lahir" id="tempat_lahir" class="form-control"
                                                type="text" readonly>
                                        </div>
                                        <label for="tgl_lahir_id" class="col-sm-2 col-form-label">Tanggal
                                            Lahir</label>
                                        <div class="col-sm-3">
                                            <input name="text" id="tgl_lahir" class="form-control" type="date"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div class="form-group row">
                                        <label for="jenis_kelamin_id" class="col-sm-2 col-form-label">Jenis
                                            Kelamin</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="jenis_kelamin"
                                                name="jenis_kelamin" readonly>
                                        </div>
                                    </div>

                                    <!-- Alamat KTP -->
                                    <div class="form-group row">
                                        <label for="alamat_ktp" class="col-sm-2 col-form-label">Alamat KTP</label>
                                        <div class="col-sm-3">
                                            <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="2" readonly></textarea>
                                        </div>
                                        <label for="alamat_domisili" class="col-sm-2 col-form-label">Alamat
                                            Domisili</label>
                                        <div class="col-sm-3">
                                            <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="2" readonly></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="npwp" class="col-sm-2 col-form-label">NPWP</label>
                                        <div class="col-sm-3">
                                            <input name="npwp" id="npwp" class="form-control" type="text"
                                                readonly>
                                        </div>
                                        <label for="jenis_pembelian" class="col-sm-2 col-form-label">Jenis
                                            Pembelian</label>
                                        <div class="col-sm-3">
                                            <input name="jenis_pembelian" id="jenis_pembelian" class="form-control"
                                                type="text" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-104">
                                    <h5>- Tagihan - </h5>
                                    <table class="table table-bordered table-tagihan">
                                        <thead>
                                            <tr class="table-primary">
                                                <th width="30px">No</th>
                                                <th>Deskripsi Tagihan</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" align="right"><b>Total Tagihan</b></td>
                                                <td align="right" id="total-tagihan"><b>Rp. 0</b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <h5>- Pemasukan - </h5>
                                    <table class="table table-bordered table-pemasukan">
                                        <thead>
                                            <tr class="table-success">
                                                <th width="30px">No</th>
                                                <th>Tanggal</th>
                                                <th>Kategori</th>
                                                <th>Deskripsi</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" align="right"><b>Total Pemasukan</b></td>
                                                <td align="right" id="total-pemasukan"><b>Rp. 0</b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-four-103">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center mx-auto"
                                                id="previewFoto"
                                                style="max-width: 600px; height: 350px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                <span style="color: #6c757d;">Tidak ada foto</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleLegend() {
            var legend = document.getElementById('legend');
            var showBtn = document.getElementById('show-btn');

            if (legend.style.right === '30px' || legend.style.right === '') {
                legend.style.right = '-300px';
                showBtn.style.display = 'block';
            } else {
                legend.style.right = '30px';
                showBtn.style.display = 'none';
            }
        }

        $(document).on('click', '.detail-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.success) {

                    const formatNumber = (number) => {
                        let num = parseFloat(number);
                        return isNaN(num) ? 0 : num.toLocaleString('id-ID');
                    };

                    $('#nama_kavling').val(response.data.lokasi.nama_kavling);
                    $('#kode_kavling').val(response.data.kode_kavling);
                    $('#panjang_kanan').val(response.data.panjang_kanan);
                    $('#panjang_kiri').val(response.data.panjang_kiri);
                    $('#lebar_depan').val(response.data.lebar_depan);
                    $('#lebar_belakang').val(response.data.lebar_belakang);
                    $('#luas_tanah').val(response.data.luas_tanah);
                    $('#luas_bangunan').val(response.data.luas_bangunan);
                    $('#hrg_meter').val(formatNumber(response.data.hrg_meter));
                    $('#tipe_bangunan').val(response.data.tipe_bangunan);
                    $('#hrg_jual').val(formatNumber(response.data.hrg_jual));
                    $('#daya_listrik').val(formatNumber(response.data.daya_listrik));
                    $('#keterangan').val(response.data.keterangan);
                    $('#no_sertifikat').val(response.data.no_sertifikat);

                    if (response.data.customer) {
                        $('#nama_lengkap').val(response.data.customer.nama_lengkap);
                        $('#nik').val(response.data.customer.nik);
                        $('#no_telp').val(response.data.customer.no_telp);
                        $('#tempat_lahir').val(response.data.customer.tempat_lahir);
                        $('#tgl_lahir').val(response.data.customer.tgl_lahir);
                        $('#jenis_kelamin').val(response.data.customer.jenis_kelamin);
                        $('#alamat_ktp').val(response.data.customer.alamat_ktp);
                        $('#alamat_domisili').val(response.data.customer.alamat_domisili);
                        $('#npwp').val(response.data.customer.npwp);
                        $('#jenis_pembelian').val(response.data.customer.jenis_pembelian);
                    } else {
                        $('#nama_lengkap, #nik, #no_telp, #tempat_lahir, #tgl_lahir, #jenis_kelamin, #alamat_ktp, #alamat_domisili, #npwp, #jenis_pembelian')
                            .val('');
                    }

                    let foto = response.data.foto;
                    let preview = $('#previewFoto');
                    if (foto) {
                        let imageUrl = '/assets/foto_kavling/' + foto;
                        preview.html(
                            `<img src="${imageUrl}" alt="Foto Kavling" style="max-height: 100%; max-width: 100%;">`
                        );
                    } else {
                        preview.html(`<span style="color: #6c757d;">Tidak ada foto</span>`);
                    }

                    let tbodyTagihan = $('.table-tagihan tbody');
                    tbodyTagihan.empty();
                    response.tagihan.forEach((row, index) => {
                        tbodyTagihan.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${row.deskripsi ?? '-'}</td>
                                <td class="text-end">Rp. ${formatNumber(row.nominal)}</td>
                            </tr>
                        `);
                    });
                    $('#total-tagihan').html(`<b>Rp. ${formatNumber(response.total_tagihan)}</b>`);

                    let tbodyPemasukan = $('.table-pemasukan tbody');
                    tbodyPemasukan.empty();
                    response.pemasukan.forEach((row, index) => {
                        let kategori = row.kategori ? row.kategori.kategori : '-';
                        tbodyPemasukan.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${formatTanggalIndo(row.tanggal)}</td>
                                <td>${kategori}</td>
                                <td>${row.keterangan ?? '-'}</td>
                                <td class="text-end">Rp. ${formatNumber(row.nominal)}</td>
                            </tr>
                        `);
                    });
                    $('#total-pemasukan').html(`<b>Rp. ${formatNumber(response.total_pemasukan)}</b>`);

                    $('#modalDetail').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#jenis_kelamin').val('').trigger('change');
            $('#status').val('').trigger('change');

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            $('#previewFoto').html('<span style="color: #6c757d;">Tidak ada foto</span>');
        });

        function formatTanggalIndo(tanggal) {
            if (!tanggal) return '-';
            const bulanIndo = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            let d = new Date(tanggal);
            let tgl = d.getDate();
            let bln = bulanIndo[d.getMonth()];
            let thn = d.getFullYear();
            return `${tgl} ${bln} ${thn}`;
        }
    </script>
    <script src={{ asset('assets/svg_1.js') }}></script>
@endpush
