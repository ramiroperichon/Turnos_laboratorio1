<!DOCTYPE html>
<html data-bs-theme="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de turnos') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @livewireStyles

</head>

<body>
    <div class="container-scroller">
        @include('components.guest-nav')
        <div class="main-panel">
            <div class="content-wrapper">
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
    <x-toaster-hub />
    <x-footer />
    @livewireScripts
    @vite('resources/js/app.js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
   <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
   <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
   <script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
   <script src="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
   <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
   <script src="{{ asset('assets/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
   <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
   <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
   <script src="{{ asset('assets/js/misc.js') }}"></script>
   <script src="{{ asset('assets/js/settings.js') }}"></script>
   <script src="{{ asset('assets/js/todolist.js') }}"></script>
   <script src="{{ asset('assets/js/file-upload.js') }}"></script>
   <script src="{{ asset('assets/js/typeahead.js') }}"></script>
   <script src="{{ asset('assets/js/select2.js') }}"></script>
   <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.map_key') }}&callback=initMap&language=es&libraries=marker"
        async defer></script>
</body>

</html>
