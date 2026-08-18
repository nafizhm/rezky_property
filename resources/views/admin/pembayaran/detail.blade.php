<!-- resources/views/admin/bank/index.blade.php -->
@extends('admin.layout_admin')
@section('content')
    <style>
        .table tr td,
        .table tr th {
            padding: 6px 10px;
            font-size: 14px;
            line-height: 1.2;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">

            </div><!-- /.container-fluid -->
        </section>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-indigo p-3">
                    <h3 class="font-weight-bold text-lg text-white">Detail Pembayaran</h3>
                </div>
                <div class="card-body">

                    <div class="row mt-2">
                        <div class="col col-7">
                            <div class="form-group row">
                                <label class="control-label col-sm-4">Nama Customer</label>
                                <div class="col-sm-7">
                                    <input name="nama_lengkap" value="{{ $customer->nama_lengkap }}" class="form-control"
                                        type="text" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4">Lokasi Perumahan</label>
                                <div class="col-sm-7">
                                    <input name="lokasi_blok" class="form-control" type="text"
                                        value="{{ $customer->lokasiKavling->nama_kavling }} # {{ $customer->kavlingPeta->kode_kavling }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4">Jenis Pembayaran</label>
                                <div class="col-sm-7">
                                    <input name="ket_cashback" id="ket_cashback" class="form-control" type="text"
                                        value="{{ $customer->jenis_pembelian }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-7 offset-sm-4">
                                    <a href="{{ route('customer.cetak-rekap', $customer->id) }}" target="_blank"
                                        class="btn btn-sm btn-primary">
                                        <i class="fa fa-print"></i> Cetak Rekap
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Awal Kanan -->
                        <div class="col col-5">
                            <div class="form-group row">
                                <label class="control-label col-sm-4">Total Tagihan</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input class="form-control text-right" type="text" id="total_tagihan_all"
                                            value="{{ number_format($customer->piutangs->sum('nominal'), 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4">Estimasi Plafon</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input class="form-control text-right format-number" type="text" id="estimasi_plafon"
                                            value="{{ number_format($customer->estimasi_plafon ?? 0, 0, ',', '.') }}"
                                            readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success" id="save-estimasi-plafon" style="padding-left:14px;padding-right:14px"><i class="fa fa-check"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4">SBUM</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input class="form-control text-right format-number" type="text" id="sbum"
                                            value="{{ number_format($customer->sbum ?? 0, 0, ',', '.') }}"
                                            readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success" id="save-sbum" style="padding-left:14px;padding-right:14px"><i class="fa fa-check"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4">Jumlah Bayar</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input class="form-control text-right" type="text" id="jumlah_bayar_all"
                                            value="{{ number_format($customer->pemasukans->sum('nominal'), 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-4">Sisa Bayar</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input class="form-control text-right" type="text" id="sisa_bayar_all"
                                            value="{{ number_format(max($customer->piutangs->sum('nominal') - ($customer->estimasi_plafon ?? 0) - ($customer->sbum ?? 0) - $customer->pemasukans->sum('nominal'), 0), 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col col-1"></div>
                        <div class="col col-10">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <h5>- Tagihan - </h5>
                                </div>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTagihan"><i
                                        class="fa fa-home"></i>
                                    Tambah Tagihan</button>
                            </div>

                            <table class="table table-bordered table-tagihan">
                                <thead>
                                    <tr class="table-primary">
                                        <th width="30px">No</th>
                                        <th>Jenis Tagihan</th>
                                        <th>Nominal</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" align="right"><b>Total Tagihan</b></td>
                                        <td align="right" id="total-tagihan"><b>Rp. 0</b></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <h5>- Pemasukan -</h5>
                                </div>
                                <div>
                                    <button class="btn btn-warning btn-sm mr-2" type="button" id="btnPencairanKpr">
                                        <i class="fa fa-university"></i> Pencairan KPR
                                    </button>
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalPemasukan"><i
                                            class="fa fa-money-bill"></i>
                                        Tambah Pemasukan</button>
                                </div>
                            </div>

                            <table class="table table-bordered table-pemasukan">
                                <thead>
                                    <tr class="table-success">
                                        <th width="30px">No</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jenis</th>
                                        <th>Nominal</th>
                                        <th width="150px">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" align="right"><b>Total Pemasukan</b></td>
                                        <td align="right"><b id="total-pemasukan">Rp. 0</b></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal pencairan kpr --}}
    <div class="modal fade" id="modalPencairanKpr" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalPencairanKprLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white font-weight-bold" id="modalPencairanKprLabel">Pencairan KPR</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPencairanKpr">
                    @csrf
                    <input type="hidden" id="pencairan_primary_id" name="primary_id" value="{{ $customer->id }}">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tgl Pencairan</label>
                            <div class="col-sm-4">
                                <input name="tanggal_pencairan" id="tanggal_pencairan" class="form-control"
                                    type="date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jumlah Plafon</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="jumlah_plafon" id="jumlah_plafon" class="form-control format-number"
                                        type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jumlah Pencairan</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="jumlah_pencairan" id="jumlah_pencairan"
                                        class="form-control format-number" type="text">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold">Retensi</h6>
                        @forelse ($retensis as $retensi)
                            <div class="form-group row retensi-row">
                                <label class="col-sm-3 col-form-label">{{ $retensi->nama_retensi }}</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input type="text" name="retensi[{{ $retensi->id }}]"
                                            class="form-control format-number retensi-input"
                                            data-retensi-id="{{ $retensi->id }}" value="0">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <small class="text-muted">{{ $retensi->keterangan ?: '-' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning mb-0">
                                Data retensi belum tersedia. Tambahkan dulu di menu master data retensi.
                            </div>
                        @endforelse

                        <div class="form-group row mt-3">
                            <label class="col-sm-3 col-form-label font-weight-bold">Total Retensi</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" id="total_retensi" class="form-control text-right" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning ms-1" id="submitBtnPencairanKpr">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal tagihan --}}
    <div class="modal fade" id="modalTagihan" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalTagihanLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalTagihanLabel">Tambah Tagihan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTagihan">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id" value="{{ $customer->id }}">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-4">Kategori Transaksi</label>
                            <div class="col-sm-8">
                                <select name="id_kategori" id="id_kategori"
                                    class="form-control select-kategori-transaksi">
                                    <option value=""></option>
                                    @foreach ($kategoriTransaksiTagihan as $data)
                                        <option value="{{ $data->id }}">{{ $data->kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Deskripsi Tagihan</label>
                            <div class="col-sm-8">
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Nominal</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" name="nominal" id="nominal"
                                        class="form-control format-number">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ms-1" id="submitBtnTagihan">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal pemasukan --}}
    <div class="modal fade" id="modalPemasukan" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalPemasukanLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title text-white font-weight-bold" id="modalPemasukanLabel">Tambah Pemasukan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formPemasukan">
                    @csrf
                    <input type="hidden" id="primary_id" name="primary_id" value="{{ $customer->id }}">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Pembayaran</label>
                            <div class="col-sm-4">
                                <input name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control"
                                    type="date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">No. Kwitansi</label>
                            <div class="col-sm-4">
                                <input name="no_kwitansi" id="no_kwitansi" class="form-control"
                                    type="text" value="{{ $defaultNoKwitansi }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Customer</label>
                            <div class="col-sm-6">
                                <input name="nasabah_b" value="{{ $customer->nama_lengkap }}" class="form-control"
                                    type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lokasi Perumahan</label>
                            <div class="col-sm-4">
                                <input name="lokasi_b" value="{{ $customer->lokasiKavling->nama_kavling }}"
                                    class="form-control" type="text" readonly>
                            </div>
                            <div class="col-sm-2">
                                <input name="kode_kavling" value="{{ $customer->kavlingPeta->kode_kavling }}"
                                    class="form-control" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kategori Transaksi</label>
                            <div class="col-sm-4">
                                <select name="id_kategori_transaksi" id="id_kategori_transaksi"
                                    class="form-control select-kategori-transaksi">
                                    <option value=""></option>
                                    @foreach ($kategoriTransaksiPemasukan as $data)
                                        <option value="{{ $data->id }}">{{ $data->kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="col-sm-2 col-form-label label-tagihan d-none">Tagihan</label>
                            <div class="col-sm-3 div-tagihan d-none">
                                <select name="id_tagihan" id="id_tagihan" class="form-control select-tagihan">
                                    <option value=""></option>
                                    @foreach ($piutang as $data)
                                        <option value="{{ $data->id }}">{{ $data->kategori->kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan Kategori</label>
                            <div class="col-sm-6">
                                <input name="keterangan_kategori" id="keterangan_kategori" class="form-control"
                                    type="text" placeholder="Contoh: DP Tahap 1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Rekening</label>
                            <div class="col-sm-4">
                                <select name="id_bank" id="id_bank" class="form-control select-rekening">
                                    <option value=""></option>
                                    @foreach ($bankList as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Metode Bayar</label>
                            <div class="col-sm-3">
                                <select name="id_metode_bayar" id="id_metode_bayar"
                                    class="form-control select-metode-bayar">
                                    <option value=""></option>
                                    @foreach ($metodeBayar as $bayar)
                                        <option value="{{ $bayar->id }}">{{ $bayar->jenis_bayar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nominal</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="nominal_bayar" id="nominal_bayar" class="form-control format-number"
                                        type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-7">
                                <textarea name="keterangan_pembayaran" id="keterangan_pembayaran" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bukti Pembayaran</label>
                            <div class="col-sm-4">
                                <input type="file" class="mb-2" id="file" name="file"
                                    accept=".jpg, .jpeg, .png, .pdf">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewLampiran"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada berkas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ms-1" id="submitBtnPemasukan">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status"
                                aria-hidden="true"></span>
                            <span class="button-text">Simpan</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- modal edit pemasukan --}}
    <div class="modal fade" id="modalEditPemasukan" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="modalEditPemasukanLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white font-weight-bold" id="modalEditPemasukanLabel">Edit Pemasukan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditPemasukan">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="edit_id_pemasukan" name="edit_id_pemasukan">
                    <input type="hidden" id="edit_primary_id" name="primary_id" value="{{ $customer->id }}">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Pembayaran</label>
                            <div class="col-sm-4">
                                <input name="tanggal_pembayaran" id="edit_tanggal_pembayaran" class="form-control" type="date">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">No. Kwitansi</label>
                            <div class="col-sm-4">
                                <input name="no_kwitansi" id="edit_no_kwitansi" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Customer</label>
                            <div class="col-sm-6">
                                <input value="{{ $customer->nama_lengkap }}" class="form-control" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Lokasi Perumahan</label>
                            <div class="col-sm-4">
                                <input value="{{ $customer->lokasiKavling->nama_kavling }}" class="form-control" type="text" readonly>
                            </div>
                            <div class="col-sm-2">
                                <input value="{{ $customer->kavlingPeta->kode_kavling }}" class="form-control" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kategori Transaksi</label>
                            <div class="col-sm-4">
                                <select name="id_kategori_transaksi" id="edit_id_kategori_transaksi" class="form-control select2-edit-pemasukan">
                                    <option value=""></option>
                                    @foreach ($kategoriTransaksiPemasukan as $data)
                                        <option value="{{ $data->id }}">{{ $data->kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan Kategori</label>
                            <div class="col-sm-6">
                                <input name="keterangan_kategori" id="edit_keterangan_kategori" class="form-control" type="text" placeholder="Contoh: DP Tahap 1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Rekening</label>
                            <div class="col-sm-4">
                                <select name="id_bank" id="edit_id_bank" class="form-control select2-edit-rekening">
                                    <option value=""></option>
                                    @foreach ($bankList as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Metode Bayar</label>
                            <div class="col-sm-3">
                                <select name="id_metode_bayar" id="edit_id_metode_bayar" class="form-control select2-edit-metode">
                                    <option value=""></option>
                                    @foreach ($metodeBayar as $bayar)
                                        <option value="{{ $bayar->id }}">{{ $bayar->jenis_bayar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nominal</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input name="nominal_bayar" id="edit_nominal_bayar" class="form-control format-number" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-7">
                                <textarea name="keterangan_pembayaran" id="edit_keterangan_pembayaran" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Bukti Pembayaran</label>
                            <div class="col-sm-4">
                                <input type="file" class="mb-2" id="edit_file" name="file" accept=".jpg, .jpeg, .png, .pdf">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="editPreviewLampiran"
                                    style="max-width: 150px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada berkas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary ms-1" id="submitBtnEditPemasukan">
                            <span class="spinner-border spinner-border-sm mx-1 d-none" role="status" aria-hidden="true"></span>
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
        previewFile('file', 'previewLampiran');

        function recalcSisaBayar() {
            let totalTagihan = unformatNumber($('#total_tagihan_all').val());
            let estimasiPlafon = unformatNumber($('#estimasi_plafon').val());
            let sbum = unformatNumber($('#sbum').val());
            let jumlahBayar = unformatNumber($('#jumlah_bayar_all').val());
            let sisa = Math.max(totalTagihan - estimasiPlafon - sbum - jumlahBayar, 0);
            $('#sisa_bayar_all').val(formatNumber(sisa));
        }

        function hitungTotalRetensi() {
            let total = 0;
            $('.retensi-input').each(function() {
                total += unformatNumber($(this).val() || 0);
            });
            $('#total_retensi').val(formatNumber(total));
            return total;
        }

        $(document).ready(function() {
            $('.select-kategori-transaksi').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Kategori Transaksi",
            });
            $('.select-tagihan').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Tagihan",
            });
            $('.select-metode-bayar').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Metode Bayar",
                minimumResultsForSearch: -1
            });
            $('.select-rekening').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Rekening",
                minimumResultsForSearch: -1
            });
        });

        $(document).ready(function() {
            $('#id_kategori_transaksi').on('change', function() {
                let val = $(this).val();
                if (val == 17) {
                    $('.label-tagihan').removeClass('d-none');
                    $('.div-tagihan').removeClass('d-none');
                } else {
                    $('.label-tagihan').addClass('d-none');
                    $('.div-tagihan').addClass('d-none');
                    $('#id_tagihan').val('');
                }
            });
        });

        $(document).on('input', '.retensi-input, #jumlah_plafon, #jumlah_pencairan', function() {
            hitungTotalRetensi();
        });

        $('#btnPencairanKpr').on('click', function() {
            let estimasiPlafon = unformatNumber($('#estimasi_plafon').val());

            if (!estimasiPlafon || estimasiPlafon <= 0) {
                audio.play();
                toastr.warning("Estimasi plafon harus diisi terlebih dahulu sebelum proses pencairan KPR.", "PERHATIAN", {
                    progressBar: true,
                    timeOut: 3500,
                    positionClass: "toast-bottom-right",
                });
                return;
            }

            $('#jumlah_plafon').val(formatNumber(estimasiPlafon));
            $('#jumlah_pencairan').val('');
            $('.retensi-input').val('0');
            hitungTotalRetensi();
            $('#modalPencairanKpr').modal('show');
        });

        $(function() {
            var customerId = '{{ $customer->id }}';
            var url = "{{ route('pembayaran.detail-tagihan', ['id' => '__id__']) }}".replace('__id__', customerId);

            $('.table-tagihan').DataTable({
                processing: false,
                serverSide: true,
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                ajax: url,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi'
                    },
                    {
                        data: 'jumlah_tagihan',
                        name: 'jumlah_tagihan',
                        className: 'text-end'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    $('#total-tagihan').html('<b>Rp. ' + settings.json.total_tagihan_formatted +
                        '</b>');
                }
            });

            $(document).on('click', '.save-nominal', function() {
                let btn = $(this);
                let id = btn.data('id');
                let input = btn.closest('.input-group').find('.edit-nominal');
                let nominal = input.val();
                let url = '{{ route('pembayaran.update-tagihan', ['id' => ':id']) }}'.replace(':id', id);

                btn.html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nominal: nominal,
                    },
                    success: function(response) {
                        if (response.success) {
                            audio.play();
                            toastr.success("Tagihan berhasil diupdate!", "BERHASIL", {
                                progressBar: true,
                                timeOut: 3500,
                                positionClass: "toast-bottom-right",
                            });
                            $('#total_tagihan_all').val(response.total_tagihan_formatted);
                            recalcSisaBayar();
                            $('.table-tagihan').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        audio.play();
                        toastr.error("Gagal mengupdate tagihan!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                        btn.html('<i class="fa fa-check"></i>');
                    }
                });
            });

            $(document).on('keypress', '.edit-nominal', function(e) {
                if (e.which === 13) {
                    $(this).closest('.input-group').find('.save-nominal').click();
                }
            });
        });

        $('#estimasi_plafon').on('click', function() {
            $(this).prop('readonly', false).focus().select();
        });

        $('#estimasi_plafon').on('keypress', function(e) {
            if (e.which === 13) {
                $(this).prop('readonly', true);
                let raw = unformatNumber($(this).val());
                $(this).val(formatNumber(raw));
                $('#save-estimasi-plafon').click();
            }
        });

        $('#estimasi_plafon').on('focusout', function() {
            let raw = unformatNumber($(this).val());
            $(this).val(formatNumber(raw));
        });

        $('#sbum').on('click', function() {
            $(this).prop('readonly', false).focus().select();
        });

        $('#sbum').on('keypress', function(e) {
            if (e.which === 13) {
                $(this).prop('readonly', true);
                let raw = unformatNumber($(this).val());
                $(this).val(formatNumber(raw));
                $('#save-sbum').click();
            }
        });

        $('#sbum').on('focusout', function() {
            let raw = unformatNumber($(this).val());
            $(this).val(formatNumber(raw));
        });

        $('#save-estimasi-plafon').on('click', function() {
            let btn = $(this);
            let estimasiPlafon = unformatNumber($('#estimasi_plafon').val());
            let id = '{{ $customer->id }}';
            let url = '{{ route('Pembayaran.update-estimasi-plafon', ['id' => ':id']) }}'.replace(':id', id);

            if (isNaN(estimasiPlafon)) {
                estimasiPlafon = 0;
            }

            btn.html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: url,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    estimasi_plafon: estimasiPlafon,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        audio.play();
                        toastr.success("Estimasi Plafon berhasil diupdate!", "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                        $('#estimasi_plafon').val(response.estimasi_plafon_formatted).prop('readonly', true);
                        $('#total_tagihan_all').val(response.total_tagihan_formatted);
                        $('#jumlah_bayar_all').val(response.jumlah_bayar_formatted);
                        $('#sisa_bayar_all').val(response.sisa_bayar_formatted);
                    }
                },
                error: function(xhr) {
                    audio.play();
                    let message = xhr.responseJSON?.message || "Gagal mengupdate estimasi plafon!";
                    toastr.error(message, "GAGAL!", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                },
                complete: function() {
                    btn.html('<i class="fa fa-check"></i>');
                }
            });
        });

        $('#save-sbum').on('click', function() {
            let btn = $(this);
            let sbum = unformatNumber($('#sbum').val());
            let id = '{{ $customer->id }}';
            let url = '{{ route('Pembayaran.update-sbum', ['id' => ':id']) }}'.replace(':id', id);

            if (isNaN(sbum)) {
                sbum = 0;
            }

            btn.html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: url,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    sbum: sbum,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        audio.play();
                        toastr.success("SBUM berhasil diupdate!", "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        $('#sbum').val(response.sbum_formatted).prop('readonly', true);
                        $('#total_tagihan_all').val(response.total_tagihan_formatted);
                        $('#jumlah_bayar_all').val(response.jumlah_bayar_formatted);
                        $('#sisa_bayar_all').val(response.sisa_bayar_formatted);
                    }
                },
                error: function(xhr) {
                    audio.play();
                    let message = xhr.responseJSON?.message || "Gagal mengupdate SBUM!";
                    toastr.error(message, "GAGAL!", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                },
                complete: function() {
                    btn.html('<i class="fa fa-check"></i>');
                }
            });
        });

        $('#modalTagihan').on('hidden.bs.modal', function() {
            $('#formTagihan')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtnTagihan');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#formTagihan').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtnTagihan');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = '{{ route('pembayaran.tambah-tagihan', ['id' => ':id']) }}'.replace(':id',
                id);
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
                success: function(response) {
                    if (response.success) {
                        $('#modalTagihan').modal('hide');
                        audio.play();
                        toastr.success("Tagihan berhasil ditambahkan!", "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        $('#total_tagihan_all').val(response.total_tagihan_formatted);
                        recalcSisaBayar();

                        $('.table-tagihan').DataTable().ajax.reload();
                    }
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
                    } else {
                        audio.play();
                        toastr.error('Terjadi kesalahan! Coba Beberapa Saat Lagi', "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $(document).on('submit', '.formHargaRumah', function(e) {
            e.preventDefault();

            let form = $(this);
            let id = form.data('id');
            let url = '{{ route('Pembayaran.update-harga-rumah', ['id' => ':id']) }}'.replace(':id', id);
            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            Swal.fire({
                title: 'Yakin update harga rumah?',
                text: "Harga akan disesuaikan dengan harga jual kavling!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="swal-btn-text">Ya, Update</span>',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: false,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary mx-2',
                    cancelButton: 'btn btn-secondary'
                },
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const confirmBtn = Swal.getConfirmButton();
                        const btnText = confirmBtn.querySelector('.swal-btn-text');

                        btnText.innerHTML =
                            `
                            <span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Mengupdate...`;
                        confirmBtn.disabled = true;

                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                audio.play();
                                toastr.success("Harga Rumah berhasil di update!",
                                    "BERHASIL", {
                                        progressBar: true,
                                        timeOut: 3500,
                                        positionClass: "toast-bottom-right",
                                    });

                                $('#total_tagihan_all').val(response
                                    .total_tagihan_formatted);
                                recalcSisaBayar();
                                $('.table-tagihan').DataTable().ajax.reload(null,
                                    false);

                                Swal.close();
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    audio.play();
                                    toastr.error("Ada inputan yang salah!",
                                        "GAGAL!", {
                                            progressBar: true,
                                            timeOut: 3500,
                                            positionClass: "toast-bottom-right",
                                        });

                                    let errors = xhr.responseJSON.errors;
                                    $.each(errors, function(key, val) {
                                        let input = $('#' + key);
                                        input.addClass('is-invalid');
                                        input.parent().find(
                                                '.invalid-feedback')
                                            .remove();
                                        input.parent().append(
                                            '<span class="invalid-feedback" role="alert"><strong>' +
                                            val[0] + '</strong></span>'
                                        );
                                    });

                                    btnText.innerHTML = 'Ya, Update';
                                    confirmBtn.disabled = false;
                                }
                            }
                        });
                    });
                }
            });
        });

        $(document).on('click', '.delete-tagihan', function(e) {
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
                            success: function(response) {
                                audio.play();
                                toastr.success("Tagihan telah dihapus!",
                                    "BERHASIL", {
                                        progressBar: true,
                                        timeOut: 3500,
                                        positionClass: "toast-bottom-right"
                                    });

                                $('.table-tagihan').DataTable().ajax.reload(null,
                                    false);
                                $('.table-pemasukan').DataTable().ajax.reload(null,
                                    false);
                                $('#total_tagihan_all').val(response
                                    .total_tagihan_formatted);
                                $('#jumlah_bayar_all').val(response
                                    .jumlah_bayar_formatted);
                                recalcSisaBayar();

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

        $(function() {
            var customerId = '{{ $customer->id }}';
            var url = "{{ route('pembayaran.detail-pemasukan', ['id' => '__id__']) }}".replace('__id__',
                customerId);

            $('.table-pemasukan').DataTable({
                processing: false,
                serverSide: true,
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                ajax: url,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        className: 'text-end'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    }
                ],
                drawCallback: function(settings) {
                    $('#total-pemasukan').html('<b>Rp. ' + settings.json.total_pemasukan_formatted +
                        '</b>');
                }
            });
        });

        $('#modalPemasukan').on('hidden.bs.modal', function() {
            $('#formPemasukan')[0].reset();
            $('.select-kategori-transaksi').val('').trigger('change');
            $('.select-tagihan').val('').trigger('change');
            $('.select-metode-bayar').val('').trigger('change');
            $('.select-rekening').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtnPemasukan');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);

            $('.label-tagihan').addClass('d-none');
            $('.div-tagihan').addClass('d-none');

            $('#previewLampiran').html(`<span style="color: #6c757d;">Tidak ada berkas</span>`);
        });

        $('#modalPencairanKpr').on('hidden.bs.modal', function() {
            $('#formPencairanKpr')[0].reset();
            $('.retensi-input').val('0');
            $('#total_retensi').val('');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtnPencairanKpr');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#formPencairanKpr').on('submit', function(e) {
            e.preventDefault();

            let estimasiPlafon = unformatNumber($('#estimasi_plafon').val());
            if (!estimasiPlafon || estimasiPlafon <= 0) {
                audio.play();
                toastr.warning("Estimasi plafon harus diisi terlebih dahulu sebelum proses pencairan KPR.", "PERHATIAN", {
                    progressBar: true,
                    timeOut: 3500,
                    positionClass: "toast-bottom-right",
                });
                return;
            }

            let jumlahPlafon = unformatNumber($('#jumlah_plafon').val());
            let jumlahPencairan = unformatNumber($('#jumlah_pencairan').val());
            let totalRetensi = hitungTotalRetensi();

            if (jumlahPlafon !== (jumlahPencairan + totalRetensi)) {
                audio.play();
                toastr.error("Jumlah plafon harus sama dengan jumlah pencairan ditambah total retensi.", "GAGAL!", {
                    progressBar: true,
                    timeOut: 3500,
                    positionClass: "toast-bottom-right",
                });
                return;
            }

            let submitBtn = $('#submitBtnPencairanKpr');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#pencairan_primary_id').val();
            let url = '{{ route('pembayaran.tambah-pencairan-kpr', ['id' => ':id']) }}'.replace(':id', id);

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#modalPencairanKpr').modal('hide');
                        audio.play();
                        toastr.success("Pencairan KPR berhasil ditambahkan!", "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        $('#jumlah_bayar_all').val(response.jumlah_bayar);
                        $('#total_tagihan_all').val(response.total_tagihan);
                        $('#sisa_bayar_all').val(response.sisa_bayar);

                        $('.table-pemasukan').DataTable().ajax.reload();
                    }
                },
                error: function(xhr) {
                    audio.play();

                    if (xhr.status === 422) {
                        let message = xhr.responseJSON.message ?? "Ada inputan yang salah!";
                        toastr.error(message, "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        let errors = xhr.responseJSON.errors || {};
                        $.each(errors, function(key, val) {
                            let input = $('[name="' + key + '"]');
                            if (!input.length) {
                                input = $('#' + key);
                            }
                            input.addClass('is-invalid');
                            input.parent().find('.invalid-feedback').remove();
                            input.parent().append(
                                '<span class="invalid-feedback" role="alert"><strong>' +
                                val[0] + '</strong></span>'
                            );
                        });
                    } else {
                        toastr.error("Gagal menambahkan pencairan KPR.", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right"
                        });
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $('#formPemasukan').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtnPemasukan');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = '{{ route('pembayaran.tambah-pemasukan', ['id' => ':id']) }}'.replace(':id',
                id);
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
                success: function(response) {
                    if (response.success) {
                        $('#modalPemasukan').modal('hide');
                        audio.play();
                        let msg = "Pemasukan berhasil ditambahkan!";
                        toastr.success(msg, "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        $('#jumlah_bayar_all').val(response.jumlah_bayar);
                        $('#total_tagihan_all').val(response.total_tagihan);
                        recalcSisaBayar();

                        $('.table-pemasukan').DataTable().ajax.reload();
                    }
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
                    } else {
                        audio.play();
                        toastr.error("Gagal menambahkan data.", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right"
                        });
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.delete-pemasukan', function(e) {
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
                            success: function(response) {
                                audio.play();
                                toastr.success("Pemasukan telah dihapus!",
                                    "BERHASIL", {
                                        progressBar: true,
                                        timeOut: 3500,
                                        positionClass: "toast-bottom-right"
                                    });

                                $('.table-pemasukan').DataTable().ajax.reload(null,
                                    false);
                                $('#jumlah_bayar_all').val(response.jumlah_bayar);
                                $('#total_tagihan_all').val(response.total_tagihan);
                                recalcSisaBayar();

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

        previewFile('edit_file', 'editPreviewLampiran');

        $(document).ready(function() {
            $('.select2-edit-pemasukan').select2({ theme: "bootstrap4", placeholder: "Pilih Kategori Transaksi" });
            $('.select2-edit-rekening').select2({ theme: "bootstrap4", placeholder: "Pilih Rekening", minimumResultsForSearch: -1 });
            $('.select2-edit-metode').select2({ theme: "bootstrap4", placeholder: "Pilih Metode Bayar", minimumResultsForSearch: -1 });
        });

        $(document).on('click', '.edit-pemasukan-button', function() {
            var url = $(this).data('url');

            $.get(url, function(response) {
                if (response.status === 'success') {
                    let data = response.data;

                    const formatNumber = (number) => {
                        let num = parseFloat(number);
                        return isNaN(num) ? '' : num.toLocaleString('id-ID');
                    };

                    $('#edit_id_pemasukan').val(data.id);
                    $('#edit_tanggal_pembayaran').val(data.tanggal);
                    $('#edit_no_kwitansi').val(data.no_kwitansi);
                    $('#edit_id_kategori_transaksi').val(data.id_kategori_transaksi).trigger('change');
                    $('#edit_keterangan_kategori').val(data.keterangan_kategori);
                    $('#edit_id_bank').val(data.id_bank).trigger('change');
                    $('#edit_id_metode_bayar').val(data.id_metode_bayar).trigger('change');
                    $('#edit_nominal_bayar').val(formatNumber(data.nominal));
                    $('#edit_keterangan_pembayaran').val(data.keterangan);

                    let preview = $('#editPreviewLampiran');
                    if (data.lampiran) {
                        let lampiranUrl = '/assets/keuangan/pemasukan/' + data.lampiran;
                        if (data.lampiran.endsWith('.pdf')) {
                            preview.html('<a href="' + lampiranUrl + '" target="_blank"><i class="fas fa-file-pdf fa-3x text-danger"></i></a>');
                        } else {
                            preview.html('<img src="' + lampiranUrl + '" alt="Lampiran" style="max-height: 100%; max-width: 100%;">');
                        }
                    } else {
                        preview.html('<span style="color: #6c757d;">Tidak ada berkas</span>');
                    }

                    $('#modalEditPemasukan').modal('show');
                }
            });
        });

        $('#modalEditPemasukan').on('hidden.bs.modal', function() {
            $('#formEditPemasukan')[0].reset();
            $('.select2-edit-pemasukan').val('').trigger('change');
            $('.select2-edit-rekening').val('').trigger('change');
            $('.select2-edit-metode').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#editPreviewLampiran').html('<span style="color: #6c757d;">Tidak ada berkas</span>');

            let submitBtn = $('#submitBtnEditPemasukan');
            submitBtn.find('.spinner-border').addClass('d-none');
            submitBtn.find('.button-text').text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#formEditPemasukan').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtnEditPemasukan');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#edit_id_pemasukan').val();
            let url = '{{ route('pembayaran.update-pemasukan', ['id' => ':id']) }}'.replace(':id', id);

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modalEditPemasukan').modal('hide');
                        audio.play();
                        toastr.success("Pemasukan berhasil diupdate!", "BERHASIL", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                        $('.table-pemasukan').DataTable().ajax.reload();
                    }
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
                            let input = $('#edit_' + key);
                            input.addClass('is-invalid');
                            input.parent().find('.invalid-feedback').remove();
                            input.parent().append('<span class="invalid-feedback" role="alert"><strong>' + val[0] + '</strong></span>');
                        });
                    } else {
                        audio.play();
                        toastr.error("Gagal mengupdate pemasukan!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });
    </script>
@endpush
