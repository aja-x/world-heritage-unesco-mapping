<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="World Heritage UNESCO Mapping">
    <meta name="author" content="Harus Rajin Club">
    <meta name="keywords" content="World Heritage UNESCO Mapping">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <title>World Heritage UNESCO Mapping</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="{{ asset('assets/admin-kit/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @yield('head')
</head>

<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar collapsed">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="javascript:void(0)">
                <span class="align-middle ">World Heritage UNESCO Map</span>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-item active">
                    <a class="sidebar-link" href="#">
                        <i class="align-middle" data-feather="map"></i> <span class="align-middle">Map</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>
            <h4 class="fw-bolder mb-0 text-muted">
                World Heritage UNESCO Map
                <span class="badge bg-primary fw-bolder mx-2" id="worldHeritageCount"></span>
            </h4>
        </nav>
        @yield('content')
        @include('layouts.footer')
    </div>
</div>
<script src="{{ asset('assets/admin-kit/js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
