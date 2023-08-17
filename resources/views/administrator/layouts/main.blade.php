<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daysf</title>

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/main/app-dark.css') }}">
    <link rel="shortcut icon" href="{{ asset('templateAdmin/assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('templateAdmin/assets/images/logo/favicon.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/shared/iconly.css') }}">

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/css/pages/fontawesome.css') }}">
    <link rel="stylesheet"
        href="{{ asset('templateAdmin/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/extensions/sweetalert2/sweetalert2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('templateAdmin/assets/extensions/toastify-js/src/toastify.css') }}">



    @stack('css')

</head>

<body>
    <div id="app">
        @include('administrator.layouts.sidebar')
        <div id="main">
            @include('administrator.layouts.header')

            @yield('content')

            @include('administrator.layouts.footer')
        </div>
    </div>
    <script src="{{ asset('jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/bootstrap.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script src="{{ asset('templateAdmin/assets/js/app.js') }}"></script>

    {{-- <!-- Need: Apexcharts -->
    <script src="{{ asset('templateAdmin/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/dashboard.js') }}"></script> --}}

    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js"></script>

    <script src="{{ asset('templateAdmin/assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('templateAdmin/assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/toastify.js') }}"></script>


    <script>
        var toastMessages = {!! json_encode([
            'errors' => session('errors', []),
            'error' => session('error'),
            'success' => session('success'),
            'warning' => session('warning'),
            'info' => session('info')
        ]) !!};
    </script>

    @stack('js')

</body>

</html>
