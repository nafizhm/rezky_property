@extends('layouts.app')

@php
    $konfigurasi = \App\Models\PengaturanProfil::first();
@endphp
<title>{{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</title>
<style>
     body {
            font-family: 'Inter', sans-serif !important;
            background: url('{{ $bg ? asset("config_media/" . $bg->nama_file) : "" }}') no-repeat center center fixed !important;
            background-size: cover !important;
            min-height: 100vh;
        }

    .icon-gradient path {
        fill: url(#gradIcon);
    }
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

</style>

@section('content')
    <div class="min-vh-100 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -1;">
            <div class="position-absolute top-0 start-0 w-100 h-100"
                 style="background: linear-gradient(to top, rgba(13, 40, 70, 0.95) 0%, rgba(13, 40, 70, 0.7) 40%, transparent 100%);"></div>
        </div>

        <div class="container py-5">
            @php
                $logo = \App\Models\PengaturanMedia::where('jenis_data', 'logo website')->first();
            @endphp

            <div class="row justify-content-center align-items-center min-vh-100">
                 <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="background: rgba(0, 0, 0, 0.5);"></div>

                <div class="col-md-8 col-lg-6">
                    <div class="text-center mb-4 animate__animated animate__fadeInDown">
                        <img src="{{ asset('config_media/' . ($logo->nama_file ?? 'default.png')) }}"
                             alt="Logo"
                             style="max-width: 120px; height: auto; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">



                            </div>

                    <div class="card border-0 shadow-lg animate__animated animate__fadeInUp"
                         style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div class="success-checkmark mx-auto mb-3">
                                    <div class="check-icon">
                                        <span class="icon-line line-tip"></span>
                                        <span class="icon-line line-long"></span>
                                        <div class="icon-circle"></div>
                                        <div class="icon-fix"></div>
                                    </div>
                                </div>
                                <h2 style="color: #1e5fa8" class="fw-bold mb-2">Booking Berhasil!</h2>
                                <p class="text-muted">Terima kasih atas kepercayaan Anda</p>
                            </div>

                            <hr class="my-4">

                            @php
                                $nama = session('nama');
                                $lokasi = session('lokasi');
                                $blok = session('blok');
                            @endphp

                            <div class="booking-details">
                                <div class="detail-item mb-3 p-3 bg-light rounded-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper mr-3">
                                           <svg class="icon-gradient" width="24" height="24" viewBox="0 0 24 24">
                                                <defs>
                                                    <linearGradient id="gradIcon" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" stop-color="#0d3b66"/>
                                                        <stop offset="100%" stop-color="#3a86ff"/>
                                                    </linearGradient>
                                                </defs>

                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Nama Pemesan</small>
                                            <strong class="text-dark">{{ $nama }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-item mb-3 p-3 bg-light rounded-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper mr-3">
                                           <svg width="24" height="24" viewBox="0 0 24 24">
                                                <defs>
                                                    <linearGradient id="gradLocation" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" stop-color="#0d3b66"/>
                                                        <stop offset="50%" stop-color="#1e5fa8"/>
                                                        <stop offset="100%" stop-color="#3a86ff"/>
                                                    </linearGradient>
                                                </defs>

                                                <path fill="url(#gradLocation)" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Lokasi Perumahan</small>
                                            <strong class="text-dark">{{ $lokasi }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-item mb-4 p-3 bg-light rounded-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper mr-3">
                                           <svg width="24" height="24" viewBox="0 0 24 24">
                                                <defs>
                                                    <linearGradient id="gradHome" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" stop-color="#0d3b66"/>
                                                        <stop offset="50%" stop-color="#1e5fa8"/>
                                                        <stop offset="100%" stop-color="#3a86ff"/>
                                                    </linearGradient>
                                                </defs>

                                                <path fill="url(#gradHome)" d="M19 9.3V4h-3v2.6L12 3 2 12h3v8h5v-6h4v6h5v-8h3l-3-2.7zm-9 .7c0-1.1.9-2 2-2s2 .9 2 2h-4z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Blok Unit Rumah</small>
                                            <strong class="text-dark">{{ $blok }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info border-0 rounded-3 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <p class="text-white mb-0 text-center">
                                    <small>
                                        <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                        </svg>
                                        Kami akan segera memproses permohonan Anda dan menghubungi Anda dalam waktu dekat
                                    </small>
                                </p>
                            </div>

                            <div class="text-center mb-4">
                                <p class="text-muted mb-0">Salam hangat dari</p>
                                <h5 style="color: #1e5fa8" class="fw-bold  mb-0">{{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</h5>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('booking') }}" class="btn-submit ">
                                    <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Success Checkmark Animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #1e5fa8;
        }

        .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }

        .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }

        .icon-line {
            height: 5px;
            background-color: #1e5fa8;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }

        .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }

        .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(76, 91, 175, 0.5);
        }

        .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background-color: #fff;
        }

        @keyframes icon-line-tip {
            0% {
                width: 0;
                left: 1px;
                top: 19px;
            }
            54% {
                width: 0;
                left: 1px;
                top: 19px;
            }
            70% {
                width: 50px;
                left: -8px;
                top: 37px;
            }
            84% {
                width: 17px;
                left: 21px;
                top: 48px;
            }
            100% {
                width: 25px;
                left: 14px;
                top: 45px;
            }
        }

        @keyframes icon-line-long {
            0% {
                width: 0;
                right: 46px;
                top: 54px;
            }
            65% {
                width: 0;
                right: 46px;
                top: 54px;
            }
            84% {
                width: 55px;
                right: 0px;
                top: 35px;
            }
            100% {
                width: 47px;
                right: 8px;
                top: 38px;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate__animated {
            animation-duration: 0.8s;
        }

        .animate__fadeInDown {
            animation-name: fadeInDown;
        }

        .animate__fadeInUp {
            animation-name: fadeInUp;
        }

        .detail-item {
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
    </style>
@endsection

@push('scripts')
    <script>
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(document).ready(function() {
            const successMsg = sessionStorage.getItem('success');
            if (successMsg) {
                audio.play();
                Swal.fire({
                    icon: 'success',
                    title: successMsg,
                    showConfirmButton: false,
                    timer: 3000
                })
                sessionStorage.removeItem('success');
            }
        });
    </script>
@endpush
