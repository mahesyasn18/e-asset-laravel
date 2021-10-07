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
    <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
</head>

<body class="{{ $class ?? '' }}">
    <div class="main-content">
        <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
            <div class="container px-4">
                <a class="navbar-brand" href="{{ route('login') }}">
                    <p style="font-size: 28px; font-weight: bold">E-Asset</p>
                </a>
            </div>
        </nav>

        @yield('content')

    </div>

    @guest()
    <footer class="py-5">
        <div class="container">
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
        </div>
    </footer>
    @endguest

    <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    @stack('js')

    <!-- Argon JS -->
    <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
</body>

</html>