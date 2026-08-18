<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $konfigurasi = \App\Models\PengaturanProfil::first();
    @endphp

    <title>{{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</title>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $favicon = \App\Models\PengaturanMedia::where('jenis_data', 'fav icon')->where('stt_aktif', 1)->first();
        $faviconPath = $favicon && $favicon->nama_file ? asset('config_media/' . $favicon->nama_file) : asset('default/favicon.ico');
    @endphp

    <link rel="icon" href="{{ $faviconPath }}">
    <link rel="shortcut icon" href="{{ $faviconPath }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.min.css" />
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('templates/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('templates/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/toastr/toastr.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">

    @stack('css')

    <style>
        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }

        .sidebar {
            position: relative;
            padding-bottom: 60px;
        }

        .sidebar .nav-sidebar {
            padding-bottom: 30px;
        }

        .sidebar-fixed-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 250px;
            background: #343a40;
            border-top: 1px solid rgba(255, 255, 255, .1);
            z-index: 1030;
        }

        .sidebar-fixed-bottom .nav-link p {
            margin: 0;
            line-height: 1;
        }

        .sidebar-fixed-bottom .nav-icon {
            margin-right: 10px;
            line-height: 1;
        }


        .sidebar-fixed-bottom .nav-link {
            color: #c2c7d0;
            padding: 12px 16px;
            display: flex;
            align-items: center;
        }

        .sidebar-fixed-bottom .nav-link:hover {
            background-color: #6610f2;
            color: #fff;
        }

        .sidebar-fixed-bottom .nav-icon {
            margin-right: 10px;
        }

        .theme-switch {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            margin-right: 14px;
            user-select: none;
        }

        .theme-switch input {
            display: none;
        }

        .theme-switch-slider {
            position: relative;
            width: 48px;
            height: 24px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .35);
            cursor: pointer;
            transition: .2s ease;
        }

        .theme-switch-slider::before {
            content: "";
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            transition: .2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .25);
        }

        .theme-switch input:checked + .theme-switch-slider {
            background: rgba(17, 24, 39, .85);
        }

        .theme-switch input:checked + .theme-switch-slider::before {
            transform: translateX(24px);
        }

        body.dark-mode {
            background-color: #111827;
            color: #e5e7eb;
        }

        body.dark-mode .content-wrapper,
        body.dark-mode .main-footer,
        body.dark-mode .modal-content {
            background-color: #111827 !important;
            color: #e5e7eb;
        }

        body.dark-mode .card,
        body.dark-mode .card-body,
        body.dark-mode .card-footer,
        body.dark-mode .modal-body,
        body.dark-mode .modal-footer,
        body.dark-mode .dropdown-menu {
            background-color: #1f2937 !important;
            color: #e5e7eb;
            border-color: #374151;
        }

        body.dark-mode .table,
        body.dark-mode .table td,
        body.dark-mode .table th,
        body.dark-mode label,
        body.dark-mode .text-dark,
        body.dark-mode .dropdown-item {
            color: #e5e7eb !important;
        }

        body.dark-mode .form-control,
        body.dark-mode .custom-select,
        body.dark-mode .select2-container--bootstrap4 .select2-selection {
            background-color: #111827 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }

        body.dark-mode .form-control[readonly],
        body.dark-mode textarea[readonly] {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
        }

        body.dark-mode .input-group-text,
        body.dark-mode .page-link {
            background-color: #374151 !important;
            color: #e5e7eb !important;
            border-color: #4b5563 !important;
        }

        body.dark-mode .table-bordered,
        body.dark-mode .table-bordered td,
        body.dark-mode .table-bordered th {
            border-color: #374151 !important;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #374151;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <script>
        if (localStorage.getItem('adminDarkMode') === '1') {
            document.body.classList.add('dark-mode');
        }
    </script>


    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('templates/img/LOGO_ICON_BEASISWA.png') }}" alt="AdminLTELogo"
                height="60" width="60">
        </div> --}}


        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light bg-indigo">
            <ul class="navbar-nav d-flex align-items-center" style="margin-left: 5px;">
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto mr-3 d-flex align-items-center">
                <li class="nav-item">
                    <label class="theme-switch mb-0" title="Dark mode">
                        <i class="fas fa-sun"></i>
                        <input type="checkbox" id="darkModeSwitch">
                        <span class="theme-switch-slider"></span>
                        <i class="fas fa-moon"></i>
                    </label>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#"
                        id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-user-circle fa-2x mr-2 text-white"></i>
                        <span class="text-white">{{ Auth::user()->username }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" onclick="logoutConfirm(event)">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>

        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            @php
                $logo = \App\Models\PengaturanMedia::where('jenis_data', 'Logo Aplikasi')->where('stt_aktif', 1)->first();
            @endphp

            <a href="#"
                class="brand-link d-flex flex-column align-items-center justify-content-center text-center py-2"
                style="text-decoration: none;">
                <img src="{{ asset('config_media/' . ($logo->nama_file ?? 'default.png')) }}" alt="Logo Aplikasi"
                    class="brand-image">
                @php
                    $konfigurasi = \App\Models\PengaturanProfil::first();
                @endphp

                {{-- <span class="brand-text font-weight-light d-block">
                    <strong>{{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</strong>
                </span> --}}
            </a>


            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        @php
                            use Illuminate\Support\Str;
                            use Illuminate\Support\Facades\Route as RouteFacade;
                            $menus = session('getmenus', collect());
                        @endphp

                        @foreach ($menus as $menu)
                            @php
                                $children = $menu->children->filter(fn ($child) => RouteFacade::has($child->route_name));
                                $hasMenuRoute = RouteFacade::has($menu->route_name);

                                if (! $hasMenuRoute && $children->isEmpty()) {
                                    continue;
                                }

                                $isActiveParent = false;
                                foreach ($children as $child) {
                                    if (
                                        request()->routeIs($child->route_name . '*') ||
                                        Str::before(request()->route()->getName(), '.') ===
                                            Str::before($child->route_name, '.')
                                    ) {
                                        $isActiveParent = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if ($children->isEmpty())
                                @php
                                    $isActiveSingle =
                                        request()->routeIs($menu->route_name . '*') ||
                                        Str::before(request()->route()->getName(), '.') ===
                                            Str::before($menu->route_name, '.');
                                @endphp
                                <li class="nav-item">
                                    <a href="{{ route($menu->route_name) }}"
                                        class="nav-link {{ $isActiveSingle ? '' : '' }}"
                                        style="{{ $isActiveSingle ? 'background-color: #6610f2 !important; color: #ffffff !important;' : '' }}">
                                        <i class="nav-icon fas {{ $menu->icon }}"
                                            style="{{ $isActiveSingle ? 'color: #ffffff !important;' : '' }}"></i>
                                        <p style="{{ $isActiveSingle ? 'color: #ffffff !important;' : '' }}">
                                            {{ $menu->title }}</p>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item {{ $isActiveParent ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ $isActiveParent ? 'active' : '' }}"
                                        style="{{ $isActiveParent ? 'background-color: #6610f2 !important; color: #ffffff !important;' : '' }}">
                                        <i class="nav-icon fas {{ $menu->icon }}"
                                            style="{{ $isActiveParent ? 'color: #ffffff !important;' : '' }}"></i>
                                        <p style="{{ $isActiveParent ? 'color: #ffffff !important;' : '' }}">
                                            {{ $menu->title }}
                                            <i class="right fas fa-angle-left"
                                                style="{{ $isActiveParent ? 'color: #ffffff !important;' : '' }}"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @foreach ($children as $child)
                                            @php
                                                $isActiveChild =
                                                    request()->routeIs($child->route_name . '*') ||
                                                    Str::before(request()->route()->getName(), '.') ===
                                                        Str::before($child->route_name, '.');
                                            @endphp
                                            <li class="nav-item">
                                                <a href="{{ route($child->route_name) }}"
                                                    class="nav-link {{ $isActiveChild ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>{{ $child->title }}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach

                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: none;">
                        </form>
                    </ul>
                </nav>
                <div class="sidebar-fixed-bottom">
                    <a href="{{ route('panduan-aplikasi.index') }}" target="_blank" class="nav-link">
                        <i class="nav-icon fas fa-info-circle"></i>
                        <p>Panduan Aplikasi</p>
                    </a>
                </div>

                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('content')

        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#"
                    class="text-gray">{{ $konfigurasi->nama_perusahaan ?? 'Template Aplikasi' }}</a>.</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <!-- jQuery -->
    <script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('templates/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('templates/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('templates/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('templates/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('templates/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('templates/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('templates/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('templates/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('templates/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('templates/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('templates/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Page specific script -->
    <!-- AdminLTE App -->
    <script src="{{ asset('templates/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ asset('templates/dist/js/demo.js') }}"></script> --}}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('templates/dist/js/pages/dashboard.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('templates/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('templates/plugins/toastr/toastr.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('templates/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <!-- Summernote -->
    <script src="{{ asset('templates/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.min.js">
    </script>

    <script>
        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        function applyDarkMode(enabled) {
            document.body.classList.toggle('dark-mode', enabled);
            $('#darkModeSwitch').prop('checked', enabled);
            localStorage.setItem('adminDarkMode', enabled ? '1' : '0');
        }

        applyDarkMode(localStorage.getItem('adminDarkMode') === '1');

        $(document).on('change', '#darkModeSwitch', function() {
            applyDarkMode(this.checked);
        });

        function formatNumber(value) {
            return value ? value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '';
        }

        function unformatNumber(value) {
            return value ? parseInt(value.replace(/\./g, '')) : 0;
        }

        $(document).on('input', '.format-number', function() {
            let input = $(this).val().replace(/[^\d]/g, '');
            let formatted = input.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formatted);
        });

        function previewFile(inputId, previewId) {
            $('#' + inputId).on('change', function() {
                const file = this.files[0];
                const previewDiv = $('#' + previewId);

                if (!file) {
                    previewDiv.html('<span style="color:#6c757d;">Tidak ada berkas</span>');
                    return;
                }

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewDiv.html(`
                    <img src="${e.target.result}" style="max-width:100%; max-height:100%;">
                `);
                    };
                    reader.readAsDataURL(file);
                    return;
                }

                if (file.type === 'application/pdf') {
                    const pdfUrl = URL.createObjectURL(file);

                    previewDiv.html(`
                <div class="d-flex justify-content-center align-items-center h-100">
                    <a href="${pdfUrl}" target="_blank">
                        Lihat Berkas
                    </a>
                </div>
            `);
                    return;
                }

                previewDiv.html('<span style="color:#6c757d;">Tidak ada berkas</span>');
            });
        }

        function setPreview(imageName, folder, previewId) {
            const preview = $('#' + previewId);

            if (imageName) {
                let imageUrl = '/' + folder + '/' + imageName;
                preview.html(`<img src="${imageUrl}" style="max-height: 100%; max-width: 100%;">`);
            } else {
                preview.html('<span style="color: #6c757d;">Tidak ada berkas</span>');
            }
        }

        $(document).on('click', '.btn-lampiran', function() {
            let file = $(this).data('file');
            let ext = file.split('.').pop().toLowerCase();

            if (ext === 'pdf') {
                $('#modalLampiran .modal-dialog')
                    .removeClass('modal-sm')
                    .addClass('modal-lg');

                $('#modalLampiran .modal-body').html(`
                    <div class="text-center">
                        <iframe src="${file}" class="w-100" style="height:80vh;"></iframe>
                    </div>
                `);
            } else {
                $('#modalLampiran .modal-dialog')
                    .removeClass('modal-lg')
                    .addClass('modal-dialog-centered modal-dialog-scrollable');

                $('#modalLampiran .modal-body').html(`
                    <div class="d-flex justify-content-center">
                        <img src="${file}" class="img-fluid" style="max-height:90vh; object-fit:contain;">
                    </div>
                `);
            }

            $('#modalLampiran').modal('show');
        });

        function logoutConfirm(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="swal-btn-text">Ya, Logout!</span>',
                cancelButtonText: 'Batal',
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
                            `<span class="spinner-border spinner-border-sm mx-2" role="status"></span> Logging out...`;
                        confirmBtn.disabled = true;

                        fetch('{{ route('refresh.csrf') }}')
                            .then(response => response.json())
                            .then(data => {
                                let form = document.getElementById('logout-form');
                                let tokenField = form.querySelector('input[name="_token"]');

                                if (!tokenField) {
                                    tokenField = document.createElement('input');
                                    tokenField.type = 'hidden';
                                    tokenField.name = '_token';
                                    form.appendChild(tokenField);
                                }

                                tokenField.value = data.token;

                                resolve();

                                form.submit();
                            })
                            .catch(() => {
                                confirmBtn.disabled = false;
                                btnText.innerHTML = `Ya, Logout!`;

                                Swal.showValidationMessage(
                                    `Gagal menghubungi server. Coba lagi.`
                                );
                            });
                    });
                }
            });
        }

        $(document).ready(function() {
            const successMsg = sessionStorage.getItem('success');
            if (successMsg) {
                audioSuccess.play();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMsg,
                    showConfirmButton: false,
                    timer: 2000
                })
                sessionStorage.removeItem('success');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
