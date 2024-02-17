<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ array_key_exists('nama_app_admin', $settings) ? $settings['nama_app_admin'] : '' }}</title>

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/main/app-dark.css') }}">

    <link rel="shortcut icon"
        href="{{ array_key_exists('favicon', $settings) ? img_src($settings['favicon'], 'settings') : '' }}"
        type="image/png">

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/shared/iconly.css') }}">

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/pages/fontawesome.css') }}">
    <link rel="stylesheet"
        href="{{ asset('templateAdmin/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset_administrator('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset_administrator('assets/plugins/sweetalert2/sweetalert2.css') }}">

    <!-- Tautan ke calendarify CSS -->
    <link rel="stylesheet" href="{{asset_administrator('assets/plugins/calendarify/dist/calendarify.min.css')}}">

    
    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/extensions/toastify-js/src/toastify.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('templateAdmin/assets/extensions/sweetalert2/sweetalert2.min.css') }}"> --}}

    {{-- <link rel="stylesheet" href="{{ asset_administrator('assets/plugins/nice-select2/dist/css/nice-select2.css') }}"> --}}

    @stack('css')

</head>

<body>
    <div id="audioContainer" class="audioContainer">
        <!-- Other content in the container -->
     </div>
    <div id="app">
        @include('administrator.layouts.sidebar')
        <div id="main">
            @include('administrator.layouts.header')

            @include('administrator.layouts.nav')

            @yield('content')

            @include('administrator.layouts.footer')
        </div>
    </div>
    <script src="{{ asset('jquery/dist/jquery.js') }}"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
        < script src = "{{ asset('templateAdmin/assets/js/bootstrap.js') }}" >
    </script>
    </script>
    <script src="{{ asset('templateAdmin/assets/js/app.js') }}"></script>
    <script src="{{ asset_administrator('assets/plugins/nice-select2/dist/js/nice-select2.js') }}"></script>

    {{-- <!-- Need: Apexcharts -->
    <script src="{{ asset('templateAdmin/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/dashboard.js') }}"></script> --}}

    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js"></script>

    {{-- <script src="{{ asset('templateAdmin/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script> --}}

    {{-- <script src="{{ asset('templateAdmin/assets/extensions/toastify-js/src/toastify.js') }}"></script> --}}
    {{-- <script src="{{ asset('templateAdmin/assets/js/pages/toastify.js') }}"></script> --}}
    <!-- Tautan ke calendarify JavaScript -->
    <script src="{{asset_administrator('assets/plugins/calendarify/dist/calendarify.iife.js')}}"></script>
    <script src="{{ asset_administrator('assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <!-- Tautan ke sweetalert JavaScript -->
    <script src="{{ asset_administrator('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset_administrator('assets/plugins/sweetalert2/toast.js') }}"></script>
    <script src="{{ asset_administrator('assets/plugins/sweetalert2/page/toast.js') }}"></script>

    

    <script>
        var toastMessages = {
            path: "{{ asset_administrator('assets/plugins/toasty/') }}",
            errors: [],
            error: @json(session('error')),
            success: @json(session('success')),
            warning: @json(session('warning')),
            info: @json(session('info'))
        };
    </script>

    @stack('js')

</body>

</html>
