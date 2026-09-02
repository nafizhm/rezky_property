@extends('admin.layout_admin')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-3">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Data Customer</h3>
                                    <div class="d-flex align-items-center">
                                        @if ($user->id_role == 1)
                                            <a href="{{ route('customer.tempo') }}" class="btn btn-sm btn-primary mr-2">
                                                <i class="fas fa-clock mr-1"></i> Customer Tempo
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger mr-2" data-toggle="modal"
                                                data-target="#modalUnitSudahLaku">
                                                <i class="fas fa-plus mr-1"></i> Unit Sudah Laku
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-dark" data-toggle="modal"
                                            data-target="#modalFilterCetak">
                                            <i class="fas fa-file mr-1"></i> Cetak Data
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table table-bordered w-100 table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Nasabah</th>
                                            <th>Marketing</th>
                                            <th>Perumahan</th>
                                            <th>Status Progres</th>
                                            <th width="200px" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>


        <!-- /.content -->
        <div class="modal fade" id="modalFilterCetak" tabindex="-1" role="dialog" data-focus="false">
            <div class="modal-dialog" role="document">
                <form action="{{ route('customer.cetak') }}" method="GET" target="_blank">
                    <div class="modal-content">
                        <div class="modal-header bg-indigo">
                            <h5 class="modal-title text-white font-weight-bold" id="modalFilterCetakLabel">Form Cetak Data
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label>Lokasi</label>
                                <select name="lokasi" class="form-control select-lokasi">
                                    <option value=""></option>
                                    @foreach ($lokasi as $l)
                                        <option value="{{ $l->id }}">{{ $l->nama_kavling }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Opsi Cetak</label>
                                <select name="tipe" class="form-control select-tipe">
                                    <option value=""></option>
                                    <option value="1">Excel</option>
                                    <option value="0">PDF</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary btn-cetak">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="btn-text">Cetak</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" data-focus="false"
            aria-labelledby="modalFormLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-indigo">
                        <h5 class="modal-title text-white font-weight-bold" id="modalFormLabel">Form Customer</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formData">
                        @csrf
                        <input type="hidden" id="primary_id" name="primary_id">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nama Lengkap <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-4">
                                    <input name="nama_lengkap" id="nama_lengkap" class="form-control" type="text">
                                </div>
                                <label class="control-label col-sm-2">NIK <span style="color: red;">*</span></label>
                                <div class="col-sm-3">
                                    <input name="nik" id="nik" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-3">Tempat Lahir <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-4">
                                    <input name="tempat_lahir" id="tempat_lahir" class="form-control" type="text">
                                </div>
                                <label class="control-label col-sm-2">Tanggal Lahir <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-3">
                                    <input name="tgl_lahir" id="tgl_lahir" class="form-control" type="date">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-3">No. Telp / WA <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-4">
                                    <input name="no_telp" id="no_telp" class="form-control" type="text">
                                </div>
                                <label class="control-label col-sm-2">Jenis Kelamin <span
                                        style="color: red;">*</span></label>
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
                                <label class="control-label col-sm-2">NPWP <span style="color: red;">*</span></label>
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
                                <label class="control-label col-sm-3">Alamat KTP <span
                                        style="color: red;">*</span></label>
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
                                    <input name="no_telp_saudara" id="no_telp_saudara" class="form-control"
                                        type="text">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="control-label col-sm-3">Lokasi Perumahan</label>
                                <div class="col-sm-4">
                                    <select class="form-control select-lokasi" disabled name="id_lokasi" id="id_lokasi">
                                        <option value=""></option>
                                        @foreach ($lokasi as $l)
                                            <option value="{{ $l->id }}">{{ $l->nama_kavling }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="control-label col-sm-2">Blok/Kav</label>
                                <div class="col-sm-3">
                                    <select name="id_kavling" id="id_kavling" disabled
                                        class="form-control select-kavling"></select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-3">Harga Rumah</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" name="hrg_jual" id="hrg_jual"
                                            class="form-control format-number" readonly>
                                    </div>
                                </div>
                                <label class="control-label col-sm-2">Biaya Surat</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" name="biaya_surat" id="biaya_surat"
                                            class="form-control format-number" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Peningkatan Mutu</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" name="peningkatan_mutu" id="peningkatan_mutu"
                                            class="form-control format-number" readonly>
                                    </div>
                                </div>
                                <label class="control-label col-sm-2">Total Harga</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" name="total_harga" id="total_harga"
                                            class="form-control format-number" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label class="control-label col-sm-3">Marketing</label>
                                <div class="col-sm-4">
                                    <select class="form-control select-marketing" disabled name="id_marketing"
                                        id="id_marketing">
                                        <option value=""></option>
                                        @foreach ($marketing as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_marketing }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-3">Jenis Perumahan</label>
                                <div class="col-sm-4">
                                    <select class="form-control select-jp" disabled name="jenis_perumahan"
                                        id="jenis_perumahan">
                                        <option value=""></option>
                                        <option value="Subsidi">Subsidi</option>
                                        <option value="Komersil">Komersil</option>
                                    </select>
                                </div>
                                <label class="control-label col-sm-2">Jenis Pembelian</label>
                                <div class="col-sm-3">
                                    <select class="form-control select-pembelian" disabled name="jenis_pembelian"
                                        id="jenis_pembelian">
                                        <option value=""></option>
                                        <option value="Pembelian Cash">Pembelian Cash</option>
                                        <option value="Cash Bertahap">Cash Bertahap</option>
                                        <option value="KPR">KPR</option>
                                    </select>
                                </div>
                            </div>

                            <!-- CASH ==================================> -->
                            <hr class="hr-transaksi" style="display: none;">
                            <div id="trx_cash" style="display: none;">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Atas Nama Surat</label>
                                    <div class="col-sm-3">
                                        <input name="an_surat_cash" id="an_surat_cash" class="form-control"
                                            type="text" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- CASH BERTAHAP ==================================> -->
                            <hr class="hr-transaksi" style="display: none;">
                            <div id="trx_cash_bertahap" style="display: none;">

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Termin (x)</label>
                                    <div class="col-sm-3">
                                        <input name="termin_x_cash_b" id="termin_x_cash_b"
                                            class="form-control format-number" type="number" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
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

    <!-- Modal Unit Sudah Laku -->
    <div class="modal fade" id="modalUnitSudahLaku" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalUnitSudahLakuLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white font-weight-bold" id="modalUnitSudahLakuLabel">
                        <i class="fas fa-plus-circle mr-1"></i> Form Unit Sudah Laku
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formDataUnitSudahLaku" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Data Pribadi -->
                        <h6 class="text-danger font-weight-bold mb-3"><i class="fas fa-user mr-1"></i> Data Pribadi</h6>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Nama Lengkap <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <input name="nama_lengkap" id="usl_nama_lengkap" class="form-control" type="text"
                                    placeholder="Masukkan nama lengkap sesuai KTP">
                            </div>
                            <label class="control-label col-sm-2">NIK <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <input name="nik" id="usl_nik" class="form-control" type="text"
                                    placeholder="Masukkan NIK">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Tempat Lahir <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <input name="tempat_lahir" id="usl_tempat_lahir" class="form-control" type="text"
                                    placeholder="Masukkan tempat lahir">
                            </div>
                            <label class="control-label col-sm-2">Tanggal Lahir <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <input name="tgl_lahir" id="usl_tgl_lahir" class="form-control" type="date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">No. Telp / WA <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <input name="no_telp" id="usl_no_telp" class="form-control" type="text"
                                    placeholder="Contoh: 08123*****">
                            </div>
                            <label class="control-label col-sm-2">Jenis Kelamin <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control select-jk-usl" name="jenis_kelamin" id="usl_jenis_kelamin">
                                    <option value=""></option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Email</label>
                            <div class="col-sm-4">
                                <input name="email" id="usl_email" class="form-control" type="text"
                                    placeholder="Contoh: xyz@gmail.com">
                            </div>
                            <label class="control-label col-sm-2">NPWP</label>
                            <div class="col-sm-3">
                                <input name="npwp" id="usl_npwp" class="form-control" type="text"
                                    placeholder="Masukan npwp">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Pekerjaan</label>
                            <div class="col-sm-4">
                                <select class="form-control select-pekerjaan-usl" name="pekerjaan" id="usl_pekerjaan">
                                    <option value=""></option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                    <option value="Pegawai Swasta">Pegawai Swasta</option>
                                    <option value="ASN">ASN</option>
                                    <option value="TNI atau Polri">TNI atau Polri</option>
                                    <option value="Karyawan BUMN">Karyawan BUMN</option>
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="Buruh">Buruh</option>
                                    <option value="Petani">Petani</option>
                                    <option value="Pedagang">Pedagang</option>
                                    <option value="Sopir">Sopir</option>
                                    <option value="Guru/Dosen">Guru/Dosen</option>
                                    <option value="Dokter">Dokter</option>
                                    <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                    <option value="Freelancer">Freelancer</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>
                            <label class="control-label col-sm-2">No. BPJS Kes</label>
                            <div class="col-sm-3">
                                <input name="no_bpjs_kes" id="usl_no_bpjs_kes" class="form-control" type="text"
                                    placeholder="Masukan BPJS Kes">
                            </div>
                        </div>

                        <div class="form-group row" id="usl_row-pekerjaan-lain" style="display: none;">
                            <label class="control-label col-sm-3">Pekerjaan Lainnya</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="pekerjaan_lain" id="usl_pekerjaan_lain"
                                    placeholder="Masukkan pekerjaan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Alamat KTP <span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <textarea name="alamat_ktp" id="usl_alamat_ktp" class="form-control" rows="2"
                                    placeholder="Alamat lengkap sesuai KTP"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Alamat Domisili <span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <textarea name="alamat_domisili" id="usl_alamat_domisili" class="form-control" rows="2"
                                    placeholder="Alamat lengkap sesuai Domisili"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Status Pernikahan <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control select-status-usl" name="status_pernikahan"
                                    id="usl_status_pernikahan">
                                    <option value=""></option>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                            </div>
                        </div>

                        <div id="usl_pasangan" style="display: none;">
                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nama Pasangan</label>
                                <div class="col-sm-4">
                                    <input name="nama_p" id="usl_nama_p" class="form-control" type="text"
                                        placeholder="Masukan Nama Pasangan">
                                </div>
                                <label class="control-label col-sm-2">NIK Pasangan</label>
                                <div class="col-sm-3">
                                    <input name="nik_p" id="usl_nik_p" class="form-control" type="text"
                                        placeholder="Masukan NIK Pasangan">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Nama Saudara</label>
                            <div class="col-sm-4">
                                <input name="nama_saudara" id="usl_nama_saudara" class="form-control" type="text"
                                    placeholder="Masukan Nama Saudara">
                            </div>
                            <label class="control-label col-sm-2">No. Telp Saudara</label>
                            <div class="col-sm-3">
                                <input name="no_telp_saudara" id="usl_no_telp_saudara" class="form-control" type="text"
                                    placeholder="Masukan No. Telp Saudara">
                            </div>
                        </div>

                        <hr>

                        <!-- Data Kavling -->
                        <h6 class="text-danger font-weight-bold mb-3"><i class="fas fa-home mr-1"></i> Data Kavling</h6>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Lokasi Perumahan <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control select-lokasi-usl" name="id_lokasi" id="usl_id_lokasi">
                                    <option value=""></option>
                                    @foreach ($lokasi as $l)
                                        <option value="{{ $l->id }}">{{ $l->nama_kavling }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="control-label col-sm-2">Blok/Kav <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <select name="id_kavling" id="usl_id_kavling"
                                    class="form-control select-kavling-usl"></select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Harga Jual</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" name="total_harga" id="usl_total_harga"
                                        class="form-control" readonly disabled placeholder="Pilih kavling terlebih dahulu">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Data Pembelian -->
                        <h6 class="text-danger font-weight-bold mb-3"><i class="fas fa-shopping-cart mr-1"></i> Data Pembelian</h6>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Marketing <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control select-marketing-usl" name="id_marketing" id="usl_id_marketing">
                                    <option value=""></option>
                                    <option value="0">Non Marketing</option>
                                    @foreach ($marketing as $m)
                                        <option value="{{ $m->id }}">{{ $m->nama_marketing }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Jenis Perumahan <span style="color: red;">*</span></label>
                            <div class="col-sm-4">
                                <select class="form-control select-jp-usl" name="jenis_perumahan" id="usl_jenis_perumahan">
                                    <option value=""></option>
                                    <option value="Subsidi">Subsidi</option>
                                    <option value="Komersil">Komersil</option>
                                </select>
                            </div>
                            <label class="control-label col-sm-2">Jenis Pembelian <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <select class="form-control select-pembelian-usl" name="jenis_pembelian"
                                    id="usl_jenis_pembelian">
                                    <option value=""></option>
                                    <option value="Pembelian Cash">Pembelian Cash</option>
                                    <option value="Cash Bertahap">Cash Bertahap</option>
                                    <option value="KPR">KPR</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <!-- File Upload -->
                        <h6 class="text-danger font-weight-bold mb-3"><i class="fas fa-upload mr-1"></i> File Upload</h6>

                        <div class="form-group row">
                            <label class="control-label col-sm-3">Foto Pemohon</label>
                            <div class="col-sm-3">
                                <input name="foto_pemohon" id="usl_foto_pemohon" type="file" accept=".jpg,.jpeg,.png">
                            </div>
                            <label class="control-label col-sm-3">Foto KTP <span style="color: red;">*</span></label>
                            <div class="col-sm-3">
                                <input name="foto_ktp" id="usl_foto_ktp" type="file" accept=".jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Foto NPWP</label>
                            <div class="col-sm-3">
                                <input name="foto_npwp" id="usl_foto_npwp" type="file" accept=".jpg,.jpeg,.png">
                            </div>
                            <label class="control-label col-sm-3">Foto KK</label>
                            <div class="col-sm-3">
                                <input name="foto_kk" id="usl_foto_kk" type="file" accept=".jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Foto BPJS</label>
                            <div class="col-sm-3">
                                <input name="foto_bpjs" id="usl_foto_bpjs" type="file" accept=".jpg,.jpeg,.png">
                            </div>
                            <label class="control-label col-sm-3">Foto KTP Pasangan</label>
                            <div class="col-sm-3">
                                <input name="foto_ktp_p" id="usl_foto_ktp_p" type="file" accept=".jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Bukti SPPR</label>
                            <div class="col-sm-3">
                                <input name="file_sppr" id="usl_file_sppr" type="file">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtnUnitSudahLaku">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.partials.modal_cetak')
@endsection

@push('scripts')
    <script>
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

        $('#modalForm').on('hidden.bs.modal', function() {
            hideAllTransactionForms();
            $('#formData')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#jenis_kelamin').val('').trigger('change');
            $('#status').val('').trigger('change');
            $('#id_lokasi').val('').trigger('change');
            $('#id_kavling').val('').trigger('change');
            $('#id_marketing').val('').trigger('change');
            $('#jenis_pembelian').val('').trigger('change');

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#modalFilterCetak').on('show.bs.modal', function() {
            $('.select-tipe').val('').trigger('change');
            $('.select-lokasi').val('').trigger('change');
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
                    }, 500);

                    $('#hrg_jual').val(formatNumber(data.hrg_jual));
                    $('#biaya_surat').val(formatNumber(data.biaya_surat));
                    $('#peningkatan_mutu').val(formatNumber(data.peningkatan_mutu));
                    $('#total_harga').val(formatNumber(data.total_harga));
                    $('#id_marketing').val(data.id_marketing).trigger('change');
                    $('#jenis_pembelian').val(data.jenis_pembelian).trigger('change');
                    $('#jenis_perumahan').val(data.jenis_perumahan).trigger('change');

                    $('#modalForm').modal('show');

                }
            });
        });

        $(document).on('change', '#jenis_pembelian', function() {
            const val = $(this).val();

            $('#trx_cash, #trx_cash_bertahap, .hr-transaksi').hide();

            if (val === 'Pembelian Cash') {
                $('#trx_cash').show();
                $('#trx_cash').prev('.hr-transaksi').show();
            }

            if (val === 'Cash Bertahap') {
                $('#trx_cash_bertahap').show();
                $('#trx_cash_bertahap').prev('.hr-transaksi').show();
            }
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

            const routeGetKavling = "{{ route('customer.getKavling', ':id') }}";
            const routeGetHarga = "{{ route('customer.getHargaKavling', ':id') }}";

            $('#id_lokasi').on('change', function() {
                let idLokasi = $(this).val();

                if (!isEditMode) {
                    $('#hrg_jual, #biaya_surat, #peningkatan_mutu, #total_harga').val('');
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

            $('.btn-cetak').on('click', function(e) {
                e.preventDefault();

                let btn = $(this);
                let form = $('#modalFilterCetak form');
                let tipe = form.find('.select-tipe').val();
                let formData = form.serialize();

                if (!tipe) {
                    alert('Pilih opsi cetak dulu');
                    return;
                }

                btn.prop('disabled', true);
                btn.find('.spinner-border').removeClass('d-none');
                btn.find('.btn-text').text('Loading...');

                if (tipe == "0") {
                    let url = form.attr('action') + "?" + formData;
                    window.open(url, '_blank');

                    btn.prop('disabled', false);
                    btn.find('.spinner-border').addClass('d-none');
                    btn.find('.btn-text').text('Cetak');
                    $('#modalFilterCetak').modal('hide');

                } else {
                    $.ajax({
                        url: form.attr('action'),
                        method: "GET",
                        data: formData,
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(data, status, xhr) {
                            let disposition = xhr.getResponseHeader('Content-Disposition');
                            let filename = "export.xlsx";
                            if (disposition && disposition.indexOf('filename=') !== -1) {
                                let matches = /filename="?(.+)"?/.exec(disposition);
                                if (matches != null && matches[1]) filename = matches[1];
                            }

                            let url = window.URL.createObjectURL(data);
                            let a = document.createElement('a');
                            a.href = url;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();

                            btn.prop('disabled', false);
                            btn.find('.spinner-border').addClass('d-none');
                            btn.find('.btn-text').text('Cetak');
                            $('#modalFilterCetak').modal('hide');
                        },
                        error: function() {
                            alert('Gagal mencetak Excel, coba lagi.');

                            btn.prop('disabled', false);
                            btn.find('.spinner-border').addClass('d-none');
                            btn.find('.btn-text').text('Cetak');
                        }
                    });
                }
            });
        });

        function formatRupiah(angka) {
            if (!angka) return '';
            return angka.replace(/\D/g, '') // hanya angka
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // ribuan
        }

        const uangFields = [
            '#pembayaran_booking',
            '#diskon_cash',
            '#pembayaran_cash',
            '#sisa_bayar_ajb',
            '#dp_cash_b',
            '#dp_kredit',
            '#cicilan_kredit'
        ];

        uangFields.forEach(function(selector) {
            $(document).on('input', selector, function() {
                let val = $(this).val().replace(/\./g, '');
                $(this).val(formatRupiah(val));
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
            let url = id ? '{{ route('customer.update', ['customer' => ':id']) }}'.replace(':id', id) :
                '{{ route('customer.store') }}';
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

                    if (response.tempo) {
                        toastr.success("Update Data Customer akan diverifikasi oleh Admin",
                            "BERHASIL", {
                                progressBar: true,
                                timeOut: 5000,
                                positionClass: "toast-bottom-right",
                            });
                    } else {
                        let msg = id ? "Data Customer berhasil diupdate!" :
                            "Data Customer berhasil ditambahkan!";
                        toastr.success(msg, "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                    }

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


        $(function() {
            var permissions = @json($permissions);
            var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: "{{ route('customer.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'tgl_terima',
                        name: 'tgl_terima',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'id_marketing',
                        name: 'id_marketing',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id_lokasi',
                        name: 'id_lokasi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'id_status_progres',
                        name: 'id_status_progres',
                        orderable: false,
                        searchable: false
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
                }]
            });
        });

        // === MODAL UNIT SUDAH LAKU ===
        $(document).ready(function() {
            $('.select-lokasi-usl').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Lokasi",
            });
            $('.select-kavling-usl').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Kavling",
            });
            $('.select-jk-usl').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Jenis Kelamin",
            });
            $('.select-marketing-usl').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Marketing",
            });
            $('.select-jp-usl').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Jenis Perumahan",
            });
            $('.select-pembelian-usl').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Jenis Pembelian",
            });
            $('.select-status-usl').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
                placeholder: "Pilih Status",
            });
            $('.select-pekerjaan-usl').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Pekerjaan",
            });
        });

        const routeGetKavlingUSL = "{{ route('customer.getKavling', ':id') }}";
        const routeGetHargaUSL = "{{ route('customer.getHargaKavling', ':id') }}";

        $(document).on('change', '#usl_id_lokasi', function() {
            let idLokasi = $(this).val();
            $('#usl_id_kavling').html('<option value="">Loading...</option>').trigger('change');
            $('#usl_total_harga').val('').attr('placeholder', 'Pilih kavling terlebih dahulu');

            if (idLokasi) {
                const urlKavling = routeGetKavlingUSL.replace(':id', idLokasi);
                $.get(urlKavling, function(data) {
                    let options = '<option value=""></option>';
                    data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.kode_kavling}</option>`;
                    });
                    $('#usl_id_kavling').html(options).trigger('change');
                });
            }
        });

        $(document).on('change', '#usl_id_kavling', function() {
            let idKavling = $(this).val();
            if (idKavling) {
                const urlHarga = routeGetHargaUSL.replace(':id', idKavling);
                $.get(urlHarga, function(data) {
                    let hargaJual = 0;
                    if (data.rincian_biaya && data.rincian_biaya.length) {
                        let hr = data.rincian_biaya.find(function(item) {
                            return item.nama === 'Harga Rumah';
                        });
                        hargaJual = hr ? hr.nilai : data.total_harga;
                    }
                    $('#usl_total_harga').val(hargaJual ? hargaJual.toLocaleString('id-ID') : '').attr('placeholder', '0');
                });
            } else {
                $('#usl_total_harga').val('').attr('placeholder', 'Pilih kavling terlebih dahulu');
            }
        });

        $(document).on('change', '#usl_status_pernikahan', function() {
            if ($(this).val() === 'Menikah') {
                $('#usl_pasangan').fadeIn();
            } else {
                $('#usl_pasangan').fadeOut();
                $('#usl_nama_p, #usl_nik_p').val('');
            }
        });

        $(document).on('change', '#usl_pekerjaan', function() {
            setTimeout(function() {
                if ($('#usl_pekerjaan').val() === 'Lain-lain') {
                    $('#usl_row-pekerjaan-lain').fadeIn();
                } else {
                    $('#usl_row-pekerjaan-lain').fadeOut();
                    $('#usl_pekerjaan_lain').val('');
                }
            }, 50);
        });

        $('#modalUnitSudahLaku').on('hidden.bs.modal', function() {
            $('#formDataUnitSudahLaku')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#usl_jenis_kelamin').val('').trigger('change');
            $('#usl_status_pernikahan').val('').trigger('change');
            $('#usl_id_lokasi').val('').trigger('change');
            $('#usl_id_kavling').html('').trigger('change');
            $('#usl_id_marketing').val('').trigger('change');
            $('#usl_jenis_pembelian').val('').trigger('change');
            $('#usl_jenis_perumahan').val('').trigger('change');
            $('#usl_pekerjaan').val('').trigger('change');
            $('#usl_pasangan').hide();
            $('#usl_row-pekerjaan-lain').hide();
            $('#usl_total_harga').val('').attr('placeholder', 'Pilih kavling terlebih dahulu');

            let submitBtn = $('#submitBtnUnitSudahLaku');
            submitBtn.find('.spinner-border').addClass('d-none');
            submitBtn.find('.button-text').text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#formDataUnitSudahLaku').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtnUnitSudahLaku');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('customer.unit-sudah-laku-store') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalUnitSudahLaku').modal('hide');
                    audio.play();
                    toastr.success("Data Unit Sudah Laku berhasil ditambahkan!", "BERHASIL", {
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
                            let input = $('#usl_' + key);
                            if (input.length) {
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
                    } else {
                        audio.play();
                        toastr.error("Terjadi kesalahan server!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
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

            if (!form.has('button.delete-button').length) return;

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

                        btnText.innerHTML = `
                    <span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>
                    Menghapus...`;
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
    </script>
    @include('admin.partials.js-cetak')
@endpush

