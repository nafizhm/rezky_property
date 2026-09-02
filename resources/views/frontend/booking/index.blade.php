@extends('layouts.app')

@section('title', 'Booking Dealaska')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
       .bg-indigo {
            background-color: #00a651 !important;
            color: #fff !important;
        }

       body {
            font-family: 'Inter', sans-serif !important;
            background: url('{{ $bg ? asset("config_media/" . $bg->nama_file) : "" }}') no-repeat center center fixed !important;
            background-size: cover !important;
            min-height: 100vh;
        }

        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 40px;
        }

        .booking-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.97);
        }

        .booking-header {
           background: linear-gradient(135deg, #0d3b66 0%, #1e5fa8 50%, #3a86ff 100%);
            padding: 24px 30px;
            text-align: center;
        }

        .booking-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            padding: 10px 14px;
            margin-bottom: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.16);
        }

        .booking-logo img {
            max-width: 110px;
            max-height: 72px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .booking-header h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        .booking-header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 4px 0 0;
            font-size: 0.85rem;
        }

        .booking-body {
            padding: 30px;
        }

        /* Stage Sections */
        .stage-section {
            margin-bottom: 28px;
        }

        .stage-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8f5e9;
        }

        .stage-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #0d3b66, #3a86ff);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .stage-title {
            font-weight: 600;
            font-size: 1rem;
            color: #0d3b66;
            margin: 0;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #374151;
            margin-bottom: 4px;
        }

        .form-control {
            border-radius: 8px !important;
            border: 1.5px solid #d1d5db !important;
            padding: 8px 12px;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            border-radius: 8px !important;
            border: 1.5px solid #d1d5db !important;
            height: 38px !important;
            padding: 0 12px !important;
            display: flex !important;
            align-items: center !important;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        /* Prevent zoom on mobile */
        @media screen and (max-width: 768px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
            padding: 0 !important;
            color: #374151;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
            line-height: 38px !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            top: 0 !important;
        }

        .form-control:focus {
            border-color: #1e5fa8 !important;
            box-shadow: 0 0 0 3px rgba(46, 165, 90, 0.15) !important;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection--single {
            border-color: #1e5fa8 !important;
            box-shadow: 0 0 0 3px rgba(46, 165, 90, 0.15) !important;
        }

        .form-control[readonly] {
            background-color: #f0fdf4;
            color: #0d3b66;
            font-weight: 500;
        }


        /* Job Card */
        .job-card {
            background: #f8faf9;
            border: 1.5px solid #e0efe5;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 14px;
            transition: all 0.3s ease;
        }

        .job-card:hover {
            border-color: #1e5fa8;
        }

        .job-card-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: #0d3b66;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .job-card-title i {
            font-size: 0.8rem;
        }

        /* File Upload */
        .payment-card {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border-left: 5px solid #0d3b66;
        }

        .bank-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            background: #fff;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .bank-info img { height: 35px; }

        .payment-amount {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0d3b66;
            letter-spacing: 0.5px;
        }

        /* File Upload Area */
        .file-upload-area {
            position: relative;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            overflow: hidden;
        }

        .file-upload-area:hover {
            border-color: #1e5fa8;
            background: #f0fdf4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #64748b;
        }

        .file-upload-label i {
            font-size: 2.2rem;
            color: #0d3b66;
            margin-bottom: 5px;
        }

        .preview-content {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .preview-media-box {
            position: relative;
            max-width: 120px;
            max-height: 100px;
            margin-bottom: 5px;
        }

        .preview-media-box img {
            max-width: 100%;
            max-height: 100px;
            border-radius: 8px;
            object-fit: contain;
            border: 1px solid #e2e8f0;
        }

        .preview-actions-overlay {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .btn-preview-action {
            padding: 4px 10px;
            font-size: 0.7rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .upload-loading {
            position: absolute;
            background: rgba(255,255,255,0.9);
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        /* Submit Button */
        .btn-submit {
            background: #1e5fa8;
            border: none;
            color: #fff;
            padding: 10px 40px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            background: #0d3b66;
            color: #fff;
        }

        .btn-submit:disabled {
            opacity: 0.7;
            transform: none;
        }

        /* Animations */
        .stage-section {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stage-section:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stage-section:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stage-section:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stage-section:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stage-section:nth-child(5) {
            animation-delay: 0.5s;
        }

        /* UTM Info Box */
        .utm-info {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1.5px solid #bbf7d0;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .utm-info i {
            color: #0d3b66;
            font-size: 1.2rem;
        }

        .utm-info span {
            color: #1a5c30;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-body {
                padding: 20px;
            }

            .income-toggle {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="booking-container">
        @php
            $logo = \App\Models\PengaturanMedia::where('jenis_data', 'Logo Login')->where('stt_aktif', 1)->first();
        @endphp

        <section class="content">
            <div class="booking-card">
                <div class="booking-header">
                    <div class="booking-logo">
                        <img src="{{ asset('config_media/' . ($logo->nama_file ?? 'default.png')) }}" alt="Logo Login">
                    </div>
                    <h4 class="fw-bold">Form Data Customer (Booking)</h4>
                     <div class="mt-2 text-center">
                        <a href="{{ route('public.siteplan.index') }}" target="_blank"
                            class="badge text-white px-3 py-2 shadow-sm"
                            style="border-radius: 50rem; font-weight: 600; font-size: 0.75rem; transition: all 0.3s; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3);">
                            <i class="fas fa-map mr-1"></i> Lihat Siteplan
                        </a>
                    </div>
                </div>
                <div class="booking-body">
                    <form id="formData" enctype="multipart/form-data">
                        @csrf
                      <div class="stage-section">
                            <div class="stage-header">
                                <div class="stage-number">1</div>
                                <h5 class="stage-title">Data Pribadi</h5>
                            </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Tanggal <span style="color: red;">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                 <div class="form-group row">
<label class="col-sm-3 col-form-label">Nama Lengkap <span
                                             style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <input name="nama_lengkap" id="nama_lengkap" class="form-control" type="text"
                                            placeholder="Masukkan nama lengkap sesuai KTP">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-3">NIK <span style="color: red;">*</span></label>
                                    <div class="col-sm-4">
                                        <input name="nik" id="nik" class="form-control" type="text"
                                        placeholder="Masukkan NIK">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Tempat Lahir <span style="color: red;">*</span></label>
                                    <div class="col-sm-4">
                                        <input name="tempat_lahir" id="tempat_lahir" class="form-control" type="text"
                                        placeholder="Masukkan tempat lahir">

                                    </div>
                                    <label class="control-label col-sm-2">Tanggal Lahir <span style="color: red;">*</span></label>
                                    <div class="col-sm-3">
                                        <input name="tgl_lahir" id="tgl_lahir" class="form-control" type="date">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-3">No. Telp / WA <span style="color: red;">*</span></label>
                                    <div class="col-sm-4">
                                        <input name="no_telp" id="no_telp" class="form-control" type="text"
                                         placeholder="Contoh: 08123*****">
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
                                        <input name="email" id="email" class="form-control" type="text"
                                        placeholder="Contoh: xyz@gmail.com">
                                    </div>
                                    <label class="control-label col-sm-2">NPWP</label>
                                    <div class="col-sm-3">
                                        <input name="npwp" id="npwp" class="form-control" type="text"
                                        placeholder="Masukan npwp">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Pekerjaan</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select-pekerjaan" name="pekerjaan"
                                            id="pekerjaan">
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
                                        <input name="no_bpjs_kes" id="no_bpjs_kes" class="form-control" type="text"
                                         placeholder="Masukan BPJS Kes">
                                    </div>
                                </div>
                                <div class="form-group row" id="row-pekerjaan-lain" style="display: none;">
                                    <label class="control-label col-sm-3">Pekerjaan Lainnya</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="pekerjaan_lain" id="pekerjaan_lain"
                                            placeholder="Masukkan pekerjaan">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Alamat KTP <span style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <textarea name="alamat_ktp" id="alamat_ktp" class="form-control" rows="2" placeholder="Alamat lengkap sesuai KTP"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Alamat Domisili <span style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <textarea name="alamat_domisili" id="alamat_domisili" class="form-control" rows="2" placeholder="Alamat lengkap sesuai Domisili"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Status Pernikahan <span style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control select-status" name="status_pernikahan"
                                            id="status_pernikahan">
                                            <option value=""></option>
                                            <option value="Belum Menikah">Belum Menikah</option>
                                            <option value="Menikah">Menikah</option>
                                            <option value="Cerai Hidup">Cerai Hidup</option>
                                            <option value="Cerai Mati">Cerai Mati</option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                            <div id="pasangan" style="display: none;">
                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Nama Pasangan</label>
                                    <div class="col-sm-4">
                                        <input name="nama_p" id="nama_p" class="form-control" type="text"
                                        placeholder="Masukan Nama Pasangan">
                                    </div>
                                    <label class="control-label col-sm-2">NIK Pasangan</label>
                                    <div class="col-sm-3">
                                        <input name="nik_p" id="nik_p" class="form-control" type="text"
                                        placeholder="Masukan NIK Pasangan">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-sm-3">Nama Saudara</label>
                                <div class="col-sm-4">
                                    <input name="nama_saudara" id="nama_saudara" class="form-control" type="text"
                                    placeholder="Masukan Nama Saudara">
                                </div>
                                <label class="control-label col-sm-2">No. Telp Saudara</label>
                                <div class="col-sm-3">
                                    <input name="no_telp_saudara" id="no_telp_saudara" class="form-control" type="text"
                                    placeholder="Masukan No. Telp Saudara">
                                </div>
                            </div>

                            <hr>

                            <div class="stage-section">
                                <div class="stage-header">
                                    <div class="stage-number">2</div>
                                    <h5 class="stage-title">Data Kavling</h5>
                                </div>

                               <div class="form-group row mb-3">
                                    <label class="control-label col-sm-3">Lokasi Perumahan <span style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control select-lokasi" name="id_lokasi" id="id_lokasi">
                                            <option value=""></option>
                                            @foreach ($lokasi as $l)
                                                <option value="{{ $l->id }}">{{ $l->nama_kavling }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label class="control-label col-sm-3">Blok/Kav <span style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="id_kavling" id="id_kavling" class="form-control select-kavling"></select>
                                    </div>
                                </div>

                                <div id="rincian-harga-container">
                                    <div class="text-muted">Pilih kavling terlebih dahulu untuk menampilkan rincian harga.</div>
                                </div>
                                <input type="hidden" name="total_harga" id="total_harga" value="">
                            </div>
                            <hr>

                             <div class="stage-section">
                                <div class="stage-header">
                                    <div class="stage-number">3</div>
                                    <h5 class="stage-title">Data Pembelian</h5>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Marketing <span style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control select-marketing" name="id_marketing" id="id_marketing">
                                            <option value=""></option>
                                            <option value="0">Non Marketing</option>
                                            @foreach ($marketing as $m)
                                                <option value="{{ $m->id }}">{{ $m->nama_marketing }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- --}}
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-3">Jenis Perumahan <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control select-jp" name="jenis_perumahan" id="jenis_perumahan">
                                            <option value=""></option>
                                            <option value="Subsidi">Subsidi</option>
                                            <option value="Komersil">Komersil</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                   <label class="control-label col-sm-3">Jenis Pembelian <span
                                            style="color: red;">*</span></label>
                                    <div class="col-sm-9">
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

                            <hr>

                             <div class="stage-section">
                                <div class="stage-header">
                                    <div class="stage-number">4</div>
                                    <h5 class="stage-title">File Upload</h5>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2">Foto Pemohon</label>
                                    <div class="col-sm-4">
                                        <input name="foto_pemohon" id="foto_pemohon" type="file" accept=".jpg,.jpeg,.png"
                                            onchange="handleFileChange(this, 'preview_foto_pemohon')">
                                        <div id="preview_foto_pemohon" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('foto_pemohon', 'preview_foto_pemohon')">Hapus</button>
                                        </div>
                                    </div>
                                    <label class="control-label col-sm-2">Foto KTP <span style="color: red;">*</span></label>
                                    <div class="col-sm-3">
                                        <input name="foto_ktp" id="foto_ktp" type="file" accept=".jpg,.jpeg,.png"
                                            onchange="handleFileChange(this, 'preview_foto_ktp')">
                                        <div id="preview_foto_ktp" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('foto_ktp', 'preview_foto_ktp')">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-sm-2">Foto NPWP</label>
                                    <div class="col-sm-4">
                                        <input name="foto_npwp" id="foto_npwp" type="file" accept=".jpg,.jpeg,.png"
                                            onchange="handleFileChange(this, 'preview_foto_npwp')">
                                        <div id="preview_foto_npwp" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('foto_npwp', 'preview_foto_npwp')">Hapus</button>
                                        </div>
                                    </div>
                                    <label class="control-label col-sm-2">Foto KK</label>
                                    <div class="col-sm-3">
                                        <input name="foto_kk" id="foto_kk" type="file" accept=".jpg,.jpeg,.png"
                                            onchange="handleFileChange(this, 'preview_foto_kk')">
                                        <div id="preview_foto_kk" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('foto_kk', 'preview_foto_kk')">Hapus</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-2">Foto BPJS</label>
                                    <div class="col-sm-4">
                                        <input name="foto_bpjs" id="foto_bpjs" type="file" accept=".jpg,.jpeg,.png"
                                            onchange="handleFileChange(this, 'preview_foto_bpjs')">
                                        <div id="preview_foto_bpjs" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('foto_bpjs', 'preview_foto_bpjs')">Hapus</button>
                                        </div>
                                    </div>
                                    <label class="control-label col-sm-2">Foto KTP Pasangan</label>
                                    <div class="col-sm-3">
                                        <input name="foto_ktp_p" id="foto_ktp_p" type="file" accept=".jpg,.jpeg,.png"
                                            onchange="handleFileChange(this, 'preview_foto_ktp_p')">
                                        <div id="preview_foto_ktp_p" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('foto_ktp_p', 'preview_foto_ktp_p')">Hapus</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-sm-2">Bukti Transfer</label>
                                    <div class="col-sm-4">
                                        <input name="file_bukti" id="file_bukti" type="file"
                                            onchange="handleFileChange(this, 'preview_file_bukti')">
                                        <div id="preview_file_bukti" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('file_bukti', 'preview_file_bukti')">Hapus</button>
                                        </div>
                                    </div>

                                    <label class="control-label col-sm-2">Bukti SPPR</label>
                                    <div class="col-sm-4">
                                        <input name="file_sppr" id="file_sppr" type="file"
                                            onchange="handleFileChange(this, 'preview_file_sppr')">
                                        <div id="preview_file_sppr" class="mt-2 d-none">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="showPreview(this)">View</button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="clearFile('file_sppr', 'preview_file_sppr')">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-submit ms-1" id="submitBtn">
                                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                                aria-hidden="true"></span>
                                            <span class=" font-weight-bold">Kirim Data</span>
                                        </button>
                                    </div>
                                </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" data-focus="false"
        aria-labelledby="previewModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-indigo">
                    <h5 class="modal-title" id="previewModalLabel">Preview File</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalPreviewImage" class="img-fluid" alt="Preview">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        function formatAngkaRibuan(angka) {
            return angka.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $(document).on('input', '#booking_fee', function() {
            let nilai = $(this).val();
            let terformat = formatAngkaRibuan(nilai);
            $(this).val(terformat);
        });

        function handleFileChange(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.getElementById(previewId);
                    const viewBtn = previewDiv.querySelector('button.btn-primary');
                    viewBtn.setAttribute('data-src', e.target.result);

                    input.style.display = 'none';
                    previewDiv.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        }

        function showPreview(modalIdOrEvent) {
            let src = '';
            if (typeof modalIdOrEvent === 'string') {
                src = document.querySelector(`[onclick="showPreview('${modalIdOrEvent}')"]`).getAttribute('data-src');
            } else {
                src = modalIdOrEvent.getAttribute('data-src');
            }

            document.getElementById('modalPreviewImage').src = src;
            const myModal = new bootstrap.Modal(document.getElementById('previewModal'));
            myModal.show();
        }


        function clearFile(inputId, previewId) {
            const input = document.getElementById(inputId);
            input.value = '';
            input.style.display = 'block';
            document.getElementById(previewId).classList.add('d-none');
            document.getElementById('preview_image').src = '';
        }

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

            $('.select-pekerjaan').select2({
                theme: "bootstrap4",
                placeholder: "Pilih Pekerjaan",
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

            const routeGetKavling = "{{ route('booking.getKavling', ':id') }}";
            const routeGetHarga = "{{ route('booking.getHargaKavling', ':id') }}";

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
                $('#id_kavling').html('<option value="">Loading...</option>').trigger('change');
                renderRincianHarga([], 0);

                if (idLokasi) {
                    const urlKavling = routeGetKavling.replace(':id', idLokasi);
                    $.get(urlKavling, function(data) {
                        let options = '<option value=""></option>';
                        data.forEach(function(item) {
                            options +=
                                `<option value="${item.id}">${item.kode_kavling}</option>`;
                        });
                        $('#id_kavling').html(options).trigger('change');
                    });
                }
            });

            $('#id_kavling').on('change', function() {
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

        function formatRupiah(angka) {
            if (!angka) return '';
            return angka.toString().replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $('#status_pernikahan').on('change', function() {
            if ($(this).val() === 'Menikah') {
                $('#pasangan').fadeIn();
                $('#nama_p, #nik_p').prop('required', true);
            } else {
                $('#pasangan').fadeOut();
                $('#nama_p, #nik_p').prop('required', false);
            }
        }).trigger('change');

        $('#pekerjaan').on('select2:select change', function(e) {
            setTimeout(function() {
                if ($('#pekerjaan').val() === 'Lain-lain') {
                    $('#row-pekerjaan-lain').fadeIn();
                } else {
                    $('#row-pekerjaan-lain').fadeOut();
                    $('#pekerjaan_lain').val('');
                }
            }, 50);
        });

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            let totalHarga = parseInt($('#total_harga').val()) || 0;
            if (totalHarga <= 0) {
                toastr.error("Harga kavling belum ditentukan!", "GAGAL!", {
                    progressBar: true,
                    timeOut: 3500,
                    positionClass: "toast-bottom-right",
                });
                spinner.addClass('d-none');
                btnText.text('Kirim Data');
                submitBtn.prop('disabled', false);
                return;
            }

            spinner.removeClass('d-none');
            btnText.text('Mengirim...');
            submitBtn.prop('disabled', true);

            let url = '{{ route('store.booking') }}';
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
                    sessionStorage.setItem('success', 'Booking berhasil dikirimkan.');
                    spinner.addClass('d-none');
                    btnText.text('Kirim Data');
                    submitBtn.prop('disabled', false);
                    window.location.href = "{{ route('booking.sukses') }}";
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
                    }
                    spinner.addClass('d-none');
                    btnText.text('Kirim Data');
                    submitBtn.prop('disabled', false);
                }
            });
        });
    </script>
@endpush

