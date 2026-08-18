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
                url('/assets/img/thumbnail.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            padding-left: 3rem;
            color: white;
        "
        >


        <div class="content position-relative" style="z-index: 2; max-width: 600px;">
            <h1 style="font-weight: 500; font-size: 3rem; margin-bottom: 0.3rem; color: white;">
                About Us
            </h1>
            <div style="width: 80px; height: 4px; background-color: white; margin-bottom: 1rem;"></div>        </div>
    </header>


    <section class="container my-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-content s-about-content pl-30">
                    <div class="about-title second-atitle" data-aos="fade-up" data-aos-delay="300">
                        <div class="about-title-flex">
                            <h2 class="about-title">{{ $aboutUs->judul ?? 'Judul tidak tersedia' }}</h2>
                            @if ($aboutUs && $aboutUs->icon)
                                <i class="{{ $aboutUs->icon }}"></i>
                            @endif
                        </div>
                    </div>
                    <p data-aos="fade-up" data-aos-delay="400">{!! $aboutUs->artikel ?? 'Konten belum tersedia.' !!}</p>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                <div class="s-about-img p-relative custom-about-img">
                    @if ($aboutUs && $aboutUs->nama_file)
                        <img src="{{ asset('konten/' . $aboutUs->nama_file) }}" alt="About Image" class="img-fluid">
                    @else
                        <img src="{{ asset('default/content.png') }}" alt="Default Image" class="img-fluid">
                    @endif
                </div>
            </div>
        </div>

            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="about-stats" style="color: #e3c37c" data-aos="fade-left" data-aos-duration="1000">
                        <div class="stat" data-aos="zoom-in" data-aos-delay="200">
                            <h2>{{ \Illuminate\Support\Facades\DB::table('kavling_peta')->count() }} </h2>
                            <p style="font-size: 16px; color: #4b4b4b; max-width: 500px; "> Total Kavling</p>
                        </div>
                        {{-- <div class="stat" data-aos="zoom-in" data-aos-delay="400">
                            <h2>1,100 <span>Ha</span></h2>
                            <p>Telah Dikembangkan</p>
                        </div> --}}
                        <div class="stat" data-aos="zoom-in" data-aos-delay="600">
                                <h2>{{ \Illuminate\Support\Facades\DB::table('lokasi_kavling')->count() }}</h2>
                            <p style="font-size: 16px; color: #4b4b4b; max-width: 500px; ">Cluster</p>
                        </div>

                        <div class="stat" data-aos="zoom-in" data-aos-delay="800">
                            <h2>{{ \App\Models\Customer::count() }}</h2>
                            <p style="font-size: 16px; color: #4b4b4b; max-width: 500px; ">Kepala Keluarga</p>
                        </div>
                    </div>
                </div>

                  <div class="col-lg-6 col-md-12" data-aos="fade-left" data-aos-duration="1000">
                    <h1 class="about-title">Rhabayu Property<br>Living In A Modern<br>International City</h1>
                    <p style="font-size: 16px; color: #4b4b4b; max-width: 500px; ">
                        Sebuah kota modern di masa depan dengan hunian berkualitas, fasilitas lengkap dan lokasi paling strategis di Batam.
                    </p>
                </div>

            </div>
    </section>


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
