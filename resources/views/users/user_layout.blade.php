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
    <link rel="stylesheet" href="{{ asset('css') }}/style.css">

</head>

<body class="{{ $class ?? '' }}">
    @include('preload/preload')
    @auth()
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @if (Auth::guard("web")->check())
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
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder" src="{{ asset('img/logo/profile.jpg') }}"
                                    class="img-fluid img-thumbnail">
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
                                <img src="{{ asset("img/logo/logo.png") }}" class="navbar-brand-img" alt="...">
                                <span class="font-weight-bold text-primary" style="font-size: 28px">E-ASSET</span>
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
                    <li class="nav-item {{ request()->is("index") ? "bg-primary" : "" }}">
                        <a class="nav-link {{ request()->is("index") ? "text-white" : "" }}" href="/index">
                            <span>
                                <i class="fas fa-tachometer-alt"></i>
                            </span>
                            <span class="ml-4">
                                {{ __('Home') }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is("request/barang") ? "bg-primary" : "" }}">
                        <a class="nav-link {{ request()->is("request/barang") ? "text-white" : "" }}"
                            href="/request/barang">
                            <span>
                                <i class="ni ni-bag-17"></i>
                            </span>
                            <span class="ml-4">
                                Request Barang
                            </span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('riwayat/transaksi') ? "bg-primary" : "" }}">
                        <a class="nav-link {{ request()->is('riwayat/transaksi') ? "text-white" : "" }}"
                            href="/riwayat/transaksi">
                            <i class="ni ni-archive-2"></i>
                            <span>Riwayat Transaksi</span>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Image placeholder" src="{{ asset('img/logo/profile.jpg') }}"
                                        class="img-fluid">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span
                                        class="mb-0 text-sm  font-weight-bold">{{ auth()->guard("web")->user()->name }}</span>
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
                                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">LMS
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

    @stack('js')

    <!-- Argon JS -->
    <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>
    <script>
        setTimeout(function() {
          $('.loader_bg').fadeToggle();
        }, 900);
    </script>
</body>

</html>