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
                                    <h3 class="font-weight-bold text-lg">Detail Data Booking</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Tanggal</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->tgl_booking_formatted }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->nama_lengkap }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">NIK</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->nik }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Jenis
                                                Kelamin</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->jenis_kelamin }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Tempat Lahir</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->tempat_lahir }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Tanggal
                                                Lahir</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->tgl_lahir_formatted }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Alamat</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->alamat }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">NPWP</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->npwp }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="no_bpjs_kes" class="col-sm-4 col-form-label">No. BPJS Kes</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="no_bpjs_kes" value="{{ $data->no_bpjs_kes }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Email</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->email }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Nomor
                                                Telepon</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->no_telp }}" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Nama
                                                Saudara</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->nama_saudara }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">No. Telp
                                                Saudara</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->no_telp_saudara }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Nama Marketing</label>
                                            <div class="col-sm-8">
                                                <input type="text"
                                                    value="{{ $data->marketing->nama_marketing ?? '-' }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Lokasi Perumahan</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->lokasi->nama_kavling ?? '-' }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Blok Kavling</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->kavling->kode_kavling ?? '-' }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="hrg_jual" class="col-sm-4 col-form-label">Harga Jual</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp.</span>
                                                    </div>
                                                    <input type="text" id="hrg_jual"
                                                        value="{{ number_format($data->hrg_jual, 0, ',', '.') }}"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="biaya_surat" class="col-sm-4 col-form-label">Biaya Surat</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp.</span>
                                                    </div>
                                                    <input type="text" id="biaya_surat"
                                                        value="{{ number_format($data->biaya_surat, 0, ',', '.') }}"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="peningkatan_mutu" class="col-sm-4 col-form-label">Peningkatan
                                                Mutu</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp.</span>
                                                    </div>
                                                    <input type="text" id="peningkatan_mutu"
                                                        value="{{ number_format($data->peningkatan_mutu, 0, ',', '.') }}"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="total_harga" class="col-sm-4 col-form-label">Total Harga</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp.</span>
                                                    </div>
                                                    <input type="text" id="total_harga"
                                                        value="{{ number_format($data->total_harga, 0, ',', '.') }}"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Jenis
                                                Pembayaran</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->jenis_pembayaran }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Jenis
                                                Perumahan</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{ $data->jenis_perumahan }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="tgl_registrasi" class="col-sm-4 col-form-label">Booking
                                                Fee</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp.</span>
                                                    </div>
                                                    <input type="text" id="booking_fee"
                                                        value="{{ number_format((float) $data->booking_fee, 0, ',', '.') }}"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <label class="form-label">Foto KTP</label>
                                                <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                    style="max-width: 350px; height: 180px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                    @if ($data->foto_ktp)
                                                        <img src="{{ asset('assets/booking/' . $data->foto_ktp) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <span style="color: #6c757d;">Tidak ada file</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Foto NPWP</label>
                                                <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                    style="max-width: 350px; height: 180px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                    @if ($data->foto_npwp)
                                                        <img src="{{ asset('assets/booking/' . $data->foto_npwp) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <span style="color: #6c757d;">Tidak ada file</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <label class="form-label">Foto KK</label>
                                                <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                    style="max-width: 350px; height: 180px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                    @if ($data->foto_kk)
                                                        <img src="{{ asset('assets/booking/' . $data->foto_kk) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <span style="color: #6c757d;">Tidak ada file</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Foto BPJS</label>
                                                <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                    style="max-width: 350px; height: 180px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                    @if ($data->foto_bpjs)
                                                        <img src="{{ asset('assets/booking/' . $data->foto_bpjs) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <span style="color: #6c757d;">Tidak ada file</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <label class="form-label">Foto KTP Pasangan</label>
                                                <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                    style="max-width: 350px; height: 180px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                    @if ($data->foto_ktp_p)
                                                        <img src="{{ asset('assets/booking/' . $data->foto_ktp_p) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <span style="color: #6c757d;">Tidak ada file</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Bukti Transfer</label>
                                                <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                    style="max-width: 350px; height: 180px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                    @if ($data->file_bukti)
                                                        <img src="{{ asset('assets/booking/' . $data->file_bukti) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <span style="color: #6c757d;">Tidak ada file</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <label class="form-label">Foto Pemohon</label>
                                                <div class="img-thumbnail d-flex align-items-center justify-content-center"
                                                    style="max-width: 350px; height: 180px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                                    @if ($data->foto_pemohon)
                                                        <img src="{{ asset('assets/booking/' . $data->foto_pemohon) }}"
                                                            style="max-width: 100%; max-height: 100%;">
                                                    @else
                                                        <span style="color: #6c757d;">Tidak ada file</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                @php
                                    $statusVerifikasi = match ($data->stt_reg) {
                                        1 => 'Pending',
                                        2 => 'Disetujui',
                                        3 => 'Ditolak',
                                        default => '-',
                                    };

                                    // $metodeBayarNama = '-';
                                    // if (!empty($data->id_metode_bayar)) {
                                    //     $metodeBayar = $metodeBayarList->firstWhere('id', $data->id_metode_bayar);
                                    //     $metodeBayarNama = $metodeBayar->jenis_bayar ?? '-';
                                    // }

                                    // $bankNama = '-';
                                    // if (!empty($data->id_bank)) {
                                    //     $bank = $bankList->firstWhere('id', $data->id_bank);
                                    //     $bankNama = $bank->nama ?? '-';
                                    // }
                                @endphp

                                <div class="form-group row">
                                    <label for="stt_reg" class="col-sm-2 col-form-label">Status Verifikasi</label>
                                    <div class="col-sm-3">
                                        <input type="text" id="stt_reg" value="{{ $statusVerifikasi }}"
                                            class="form-control" readonly>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Metode Bayar</label>
                                    <div class="col-sm-3">
                                        <input type="text" id="id_metode_bayar" value="{{ $metodeBayarNama }}"
                                            class="form-control" readonly>
                                    </div>
                                    <label for="id_bank" class="col-sm-2 col-form-label">Rekening Pembayaran</label>
                                    <div class="col-sm-3">
                                        <input type="text" id="id_bank" value="{{ $bankNama }}"
                                            class="form-control" readonly>
                                    </div>
                                </div> --}}

                                <div class="form-group row">
                                    <label for="jenis_pembelian" class="col-sm-2 col-form-label">Jenis
                                        Pembelian</label>
                                    <div class="col-sm-3">
                                        <input type="text" id="jenis_pembelian"
                                            value="{{ $data->jenis_pembelian ?? '-' }}" class="form-control" readonly>
                                    </div>
                                </div>

                                <!-- CASH ==================================> -->
                                @if ($data->jenis_pembelian == 'Pembelian Cash')
                                    <hr>
                                    <div id="trx_cash">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Atas Nama Surat</label>
                                            <div class="col-sm-3">
                                                <input type="text" id="an_surat_cash"
                                                    value="{{ $data->an_surat_cash ?? $data->nama_lengkap }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- CASH BERTAHAP ==================================> -->
                                @if ($data->jenis_pembelian == 'Cash Bertahap')
                                    <hr>
                                    <div id="trx_cash_bertahap">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Termin (x)</label>
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <input type="text" id="termin_x_cash_b"
                                                        value="{{ isset($data->termin_x_cash_b) && $data->termin_x_cash_b != 0 ? number_format($data->termin_x_cash_b, 0, ',', '.') : 60 }}"
                                                        class="form-control" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Bulan</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="modal-footer text-center">
                                    <a href="{{ route('pengajuan-hold.arsip') }}" class="btn btn-danger">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
