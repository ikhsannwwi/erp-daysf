<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daysf</title>
        
        <link rel="stylesheet" href="{{asset('templateAdmin/assets/css/main/app.css')}}">
        <link rel="stylesheet" href="{{asset('templateAdmin/assets/css/main/app-dark.css')}}">
        <link rel="shortcut icon" href="{{asset('templateAdmin/assets/images/logo/favicon.svg')}}" type="image/x-icon">
        <link rel="shortcut icon" href="{{asset('templateAdmin/assets/images/logo/favicon.png')}}" type="image/png">
        
        <link rel="stylesheet" href="{{asset('templateAdmin/assets/css/shared/iconly.css')}}">

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
        <script src="{{asset('templateAdmin/assets/js/bootstrap.js')}}"></script>
        <script src="{{asset('templateAdmin/assets/js/app.js')}}"></script>
        
        <!-- Need: Apexcharts -->
        <script src="{{asset('templateAdmin/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
        <script src="{{asset('templateAdmin/assets/js/pages/dashboard.js')}}"></script>

        @stack('js')

    </body>

</html>
