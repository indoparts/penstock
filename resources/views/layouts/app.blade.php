<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>App Name - @yield('title')</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    @section('css')
    @show
</head>

@auth

    <body id="page-top">
    @endauth
    @guest

        <body class="bg-gradient-primary">
        @endguest
        @guest
            <div class="container">
                @yield('content')
            </div>
        @endguest
        @auth
            <div id="app">
                <div id="wrapper">
                    @section('sidebar')
                    @show
                    <div id="content-wrapper" class="d-flex flex-column">
                        <div id="content">
                            @section('navbar')
                            @show
                            <div class="container-fluid">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
                @auth
                    <a class="scroll-to-top rounded" href="#page-top">
                        <i class="fas fa-angle-up"></i>
                    </a>
                @endauth
            </div>
        @endauth
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
        @section('js')
        @show
    </body>

</html>
