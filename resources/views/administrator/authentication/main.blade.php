<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ array_key_exists('nama_app_admin', $settings) ? $settings['nama_app_admin'] : '' }}</title>
    <link rel="stylesheet" href="{{asset('templateAdmin/assets/css/main/app.css')}}">
    <link rel="stylesheet" href="{{asset('templateAdmin/assets/css/pages/auth.css')}}">
    <link rel="shortcut icon" href="{{ array_key_exists('favicon', $settings) ? img_src($settings['favicon'], 'settings') : '' }}" type="image/png">

    <link rel="stylesheet" href="{{ asset_administrator('assets/plugins/sweetalert2/sweetalert2.css') }}">


    @stack('css')
</head>

<body>
    <div id="audioContainer" class="audioContainer">
        <!-- Other content in the container -->
     </div>
    @yield('content')


    <script src="{{ asset('jquery/dist/jquery.js') }}"></script>
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
