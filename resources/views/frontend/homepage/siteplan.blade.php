<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        @php
        $konfigurasi = \App\Models\PengaturanProfil::first();
        @endphp

        <title>{{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</title>
        @php
        $logoIcon = \App\Models\PengaturanMedia::where('jenis_data', 'fav icon')->first();
        @endphp

        @if($logoIcon && $logoIcon->nama_file)
        <link rel="icon" href="{{ asset('config_media/' . $logoIcon->nama_file) }}">
        @endif
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">

        <link rel="stylesheet" href="{{ asset('templateHomepage/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('templateHomepage/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('templateHomepage/css/magnific-popup.css') }}">
        <link rel="stylesheet" href="{{ asset('templateHomepage/css/dripicons.css') }}">
        <link rel="stylesheet" href="{{ asset('templateHomepage/css/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('templateHomepage/css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('templateHomepage/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('templateHomepage/css/responsive.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.min.css" />
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    </head>
    <body>

    <header
        class="header-section position-relative d-flex align-items-center"
        style="
            height: 50vh;
            background-image:
            linear-gradient(rgba(0, 45, 90, 0.50), rgb(103, 17, 242)),
            url('/assets/img/thumnail-marketing.webp');
            padding-left: 3rem;
            color: white;
        ">


        <div class="content position-relative" style="z-index: 2; max-width: 600px;">
            <h1 class="judul-siteplan" style="font-weight: 500; margin-bottom: 0.3rem; color: white;">
                Siteplan
            </h1>
            <div style="width: 80px; height: 4px; background-color: white; margin-bottom: 1rem;"></div>
        </div>
    </header>



<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-body bg-light text-center">
            {{-- Judul & Alamat --}}
            <h2 class="fw-bold">{{ $kav->nama_kavling }}</h2>
            <p class="text-muted">{{ $kav->alamat }}</p>

            {{-- SVG Container --}}
            <div class="d-flex justify-content-center">
                <div class="svg-container position-relative">
                    {{-- SVG Header --}}
                    @if ($kav->masterSvg)
                        {!! str_replace(['[[lebar]]', '[[tinggi]]'], ['100%', '400px'], $kav->masterSvg->header_svg) !!}
                    @endif

                    {{-- Loop kavling --}}
                    @foreach ($kav->kavlingPeta as $pt)
                        @php
                            $warna = '#ffffff';
                            if ($pt->customer) {
                                if ($pt->progres) {
                                    $warna = $pt->progres->warna;
                                }
                            } else {
                                if ($pt->status == 1) {
                                    $warna = '#42f202';
                                }
                            }
                        @endphp

                        @if ($pt->jenis_map == 'polygon')
                            {!! str_replace(
                                ['[[1]]', '[[2]]', '[[3]]', '[[4]]'],
                                [$pt->map, $warna, $pt->matrik, $pt->kode_kavling],
                                $kav->masterSvg->polygon_svg,
                            ) !!}
                        @elseif ($pt->jenis_map == 'path')
                            {!! str_replace(
                                ['[[1]]', '[[2]]', '[[3]]', '[[4]]'],
                                [$pt->map, $warna, $pt->matrik, $pt->kode_kavling],
                                $kav->masterSvg->path_svg,
                            ) !!}
                        @endif
                    @endforeach

                    {{-- SVG Footer --}}
                    @if ($kav->masterSvg)
                        {!! $kav->masterSvg->footer_svg !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>





   <footer id="{{ $tertiaryContent->url_item ?? 'contact' }}" class="footer-bg footer-p pt-100 pb-80 custom-footer" >
    <div class="footer-top pb-30">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-6 col-lg-6 col-sm-6">
                    <div class="footer-widget mb-30">
                        <div class="footer-map mb-30">
                            <iframe
                                src="https://www.google.com/maps?q=1.067222,103.954389&hl=es;z=14&amp;output=embed"
                                width="100%"
                                height="250"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>

                @foreach($footer as $footerItem)
                    @if($footerItem->judul && $footerItem->artikel)
                        <div class="col-xl-6 col-lg-3 col-sm-6">
                            <div class="footer-widget mb-30">
                                <div class="f-widget-title">
                                    <h5>{{ $footerItem->judul }}</h5>
                                </div>
                                <div class="footer-link">
                                    {!! nl2br(e($footerItem->artikel)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

             @if($tertiaryContent && $tertiaryContent->judul && $tertiaryContent->artikel)
                <div class="col-12">
                    <div class="footer-widget mb-30 text-end">
                        <div class="f-widget-title">
                            <h5 class=" text-white d-inline-block px-3 py-2 rounded">
                                <i class="fas fa-phone-alt me-2"></i> {{ $tertiaryContent->judul }}
                            </h5>
                            <p class="text-white mt-2">{{ $tertiaryContent->artikel }}</p>

                            <a href="#" class="btn btn-info mt-2">
                                <i class="fas fa-envelope me-1"></i> Kontak Kami
                            </a>
                        </div>
                    </div>
                </div>
            @endif


            </div>
        </div>
    </div>
    <div class="copyright-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @php
                    $konfigurasi = \App\Models\PengaturanProfil::first();
                    @endphp
                    <div class="copyright-text text-center">
                        <p>&copy; 2025 {{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>



<script src="{{ asset('templateHomepage/js/vendor/modernizr-3.5.0.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/one-page-nav-min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/slick.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/ajax-form.js') }}"></script>
<script src="{{ asset('templateHomepage/js/paroller.js') }}"></script>
<script src="{{ asset('templateHomepage/js/wow.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/js_isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/imagesloaded.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/parallax.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('templateHomepage/js/element-in-view.js') }}"></script>
<script src="{{ asset('templateHomepage/js/main.js') }}"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

</body>
<script>
     AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });


document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});


</script>



</html>
