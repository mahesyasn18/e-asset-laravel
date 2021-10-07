<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Argon Dashboard') }}</title>
    <!-- Favicon -->
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Extra details for Live View on GitHub Pages -->

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/fontawesome.min.css"
        integrity="sha512-shT5e46zNSD6lt4dlJHb+7LoUko9QZXTGlmWWx0qjI9UhQrElRb+Q5DM7SVte9G9ZNmovz2qIaV7IWv0xQkBkw=="
        crossorigin="anonymous" />
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="{{ asset('css') }}/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<body class="{{ $class ?? '' }}">
    @include('preload/preload')
    @auth()
    <form id="logout-form" action="{{ route('logout_admin') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @if (Auth::guard("admin")->check())
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
        <div class="container-fluid">
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
                aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Brand -->
            <a class="navbar-brand pt-0" href="{{ route('dashboard') }}">
                <img src="{{ asset("img/logo/logo.png") }}" class="navbar-brand-img" alt="...">
                <span class="font-weight-bold text-primary" style="font-size: 28px">E-ASSET</span>
            </a>
            <!-- User -->
            <ul class="nav align-items-center d-md-none">
                <li class="nav-item mr-2" style="font-size: 22px">
                    <a href="" class="text-dark"><span><i class="fas fa-bell" aria-hidden="true"></i></span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder" src="{{ asset('img/logo/profile.jpg') }}">
                            </span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout_admin') }}" class="dropdown-item" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            <i class="ni ni-user-run"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                    </div>
                </li>
            </ul>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Collapse header -->
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="{{ route('dashboard') }}">
                                <img src="{{ asset("img/logo/logo.png") }}" alt="">
                                <span class="h2 text-primary">E-ASSET</span>
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <button type="button" class="navbar-toggler" data-toggle="collapse"
                                data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                                aria-label="Toggle sidenav">
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Form -->
                <!-- Navigation -->
                <ul class="navbar-nav">
                    <li class="nav-item {{ request()->is("dashboard")?"bg-primary":"" }}">
                        <a class="nav-link {{ request()->is("dashboard")?"text-white":"" }}"
                            href="{{ route('dashboard') }}">
                            <span>
                                <i class="fas fa-tachometer-alt"></i>
                            </span>
                            <span class="ml-4">
                                {{ __('Dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is("asset/create")?"bg-primary":"" }}">
                        <a class="nav-link {{ request()->is("asset/create")?"text-white":"" }}" href="/asset/create">
                            <span>
                                <i class="fas fa-box-open"></i>
                            </span>
                            <span class="ml-4">
                                Asset
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-user" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-examples">
                            <i class="fas fa-user-alt"></i>
                            <span class="nav-link-text">Data Akun</span>
                        </a>

                        <div class="collapse {{ request()->is("akun/admin") || request()->is("akun/siswa") || request()->is("akun/siswa/create") || request()->is("akun/admin/create") || request()->is("akun/guru") || request()->is("akun/guru/create") ? "show" : "" }}"
                            id="navbar-user">
                            <ul class="nav nav-sm flex-column">
                                <li
                                    class="nav-item {{ request()->is("akun/admin") || request()->is("akun/admin/create") ? "bg-primary":"" }}">
                                    <a class="nav-link {{ request()->is("akun/admin") || request()->is("akun/admin/create") ? "text-white":"" }}"
                                        href="/akun/admin">
                                        Admin
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->is("akun/siswa") || request()->is("akun/siswa/create") ? "bg-primary":"" }}">
                                    <a class="nav-link {{ request()->is("akun/siswa") || request()->is('akun/siswa/create') ? "text-white":"" }}"
                                        href="/akun/siswa">
                                        Siswa
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ request()->is("akun/guru") || request()->is("akun/guru/create") ? "bg-primary":"" }}">
                                    <a href="/akun/guru"
                                        class="nav-link {{ request()->is("akun/guru") || request()->is("akun/guru/create") ? "text-white":"" }}">
                                        Guru
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#data-log" data-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="navbar-examples">
                            <i class="fas fa-th-list"></i>
                            <span class="nav-link-text">Logs</span>
                        </a>

                        <div class="collapse {{ request()->is("activity-log") || request()->is("request-log") ? "show" : "" }}"
                            id="data-log">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item {{ request()->is("activity-log") ? 'bg-primary' : "" }}">
                                    <a href="/activity-log"
                                        class="nav-link {{ request()->is("activity-log") ? 'text-white  ' : "" }}">
                                        <span class="nav-link-text">Activity Log</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->is("request-log") ? 'bg-primary' : '' }}">
                                    <a href="/request-log"
                                        class="nav-link {{ request()->is("request-log") ? 'text-white' : '' }}">
                                        <span class="nav-link-text">Request Log</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#data-additional" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-examples">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <span class="nav-link-text">Additional</span>
                        </a>

                        <div class="collapse {{ request()->is("tahun-ajaran") || request()->is("daftar/jurusan") || request()->is("sumber-barang") || request()->is('daftar-kelas') ? "show" : "" }}"
                            id="data-additional">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item {{ request()->is("tahun-ajaran") ? 'bg-primary' : "" }}">
                                    <a href="/tahun-ajaran"
                                        class="nav-link {{ request()->is("tahun-ajaran") ? 'text-white' : "" }}">
                                        <span>Tahun Ajaran</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->is("daftar/jurusan") ? 'bg-primary' : "" }}">
                                    <a href="/daftar/jurusan"
                                        class="nav-link {{ request()->is("daftar/jurusan") ? 'text-white' : "" }}">
                                        <span>Daftar Jurusan</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->is("sumber-barang") ? 'bg-primary' : "" }}">
                                    <a href="/sumber-barang"
                                        class="nav-link {{ request()->is("sumber-barang") ? 'text-white' : "" }}">
                                        <span>Sumber Barang</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->is("daftar-kelas") ? 'bg-primary' : "" }}">
                                    <a href="/daftar-kelas"
                                        class="nav-link {{ request()->is("daftar-kelas") ? 'text-white' : "" }}">
                                        <span>Daftar Kelas</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li
                        class="nav-item {{ request()->is("transaksi/keluar/pending") || request()->is("transaksi/keluar/approve") || request()->is("transaksi/keluar/ongoing") || request()->is("transaksi/keluar/completed") || request()->is("transaksi/keluar/cancel") ? "bg-primary":"" }}">
                        <a class="nav-link {{ request()->is("transaksi/keluar/pending") || request()->is("transaksi/keluar/approve") || request()->is("transaksi/keluar/ongoing") || request()->is("transaksi/keluar/completed") || request()->is("transaksi/keluar/cancel") ? "text-white":"" }}"
                            href="/transaksi/keluar/pending">
                            <i class="fas fa-exchange-alt"></i>
                            <span class="nav-link-text">Transaksi Keluar</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is("pinjam/langsung") ? 'bg-primary' : '' }}">
                        <a class="nav-link {{ request()->is("pinjam/langsung") ? 'text-white' : '' }}"
                            href="/pinjam/langsung">
                            <i class="fas fa-truck-loading"></i>
                            <span>Pinjam Langsung</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endif
    @endauth

    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
                    href="{{ route('dashboard') }}">{{ $title }}</a>
                <!-- Form -->
                <!-- User -->
                <ul class="navbar-nav align-items-center d-none d-md-flex">
                    <li class="nav-item dropdown ml-2">
                        <a href="" class="text-white" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><span style="font-size: 22px"><i class="fas fa-bell"
                                    aria-hidden="true">
                                    <span class="badge text-white" style="background-color: red"
                                        id="notif-count"></span></i></span></a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">{{ __('Notifications') }}</h6>
                            </div>

                            <div id="notif-content">

                            </div>

                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Image placeholder" src="{{ asset('img/logo/profile.jpg') }}">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span
                                        class="mb-0 text-sm  font-weight-bold">{{ auth()->guard("admin")->user()->name }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout_admin') }}" class="dropdown-item" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="ni ni-user-run"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        @yield('content')

        <div class="container-fluid">
            <footer class="footer">
                <div class="row align-items-center justify-content-xl-between">
                    <div class="col-xl-6">
                        <div class="copyright text-center text-xl-left text-muted">
                            &copy; {{ now()->year }} <a href="#" class="font-weight-bold ml-1" target="_blank">E-Asset
                                Tim</a> &amp;
                            <a href="#" class="font-weight-bold ml-1" target="_blank">Rekayasa
                                Perangkat Lunak</a>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                            <li class="nav-item">
                                <a href="https://www.smkn1-cmi.sch.id/" class="nav-link" target="_blank">SMKN 1
                                    Cimahi</a>
                            </li>
                            <li class="nav-item">
                                <a href="http://infoku.smkn1-cmi.sch.id/" class="nav-link" target="_blank">Info
                                    Kurikulum</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://lms.smkn1-cmi.sch.id/" class="nav-link" target="_blank">LMS
                                    SMKN 1 Cimahi</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @guest()
    @endguest

    <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <!-- Argon JS -->
    <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('js')
    <script>
        setTimeout(function() {
          $('.loader_bg').fadeToggle();
        }, 900);
    </script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('2fd731adefcda8517b9f', {
            cluster: 'ap1'
        });
        var channel = pusher.subscribe('request-channel');
        channel.bind("request-event",function(data){
        console.log(data.message.length);
        $("#notif-content").append(`
            <a href='/transaksi/keluar/pending' class='dropdown-item'><span>`+data.message+` `+data.name+`</span></a>
        `)
        $("#notif-count").html("New");
        toastr.options = {
            "closeButton": true,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            }
            toastr.success(data.message+" "+data.name);
        })
    </script>
</body>

</html>