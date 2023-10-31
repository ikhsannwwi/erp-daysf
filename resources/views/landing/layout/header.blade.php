<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>@yield('title') - SanApp</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('cyborg/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{asset('cyborg/assets/css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('cyborg/assets/css/templatemo-cyborg-gaming.css')}}">
    <link rel="stylesheet" href="{{asset('cyborg/assets/css/owl.css')}}">
    <link rel="stylesheet" href="{{asset('cyborg/assets/css/animate.css')}}">
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css')}}"/>
<!--

TemplateMo 579 Cyborg Gaming

https://templatemo.com/tm-579-cyborg-gaming

-->
  </head>

<body>

  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.html" class="logo">
                        <img src="{{asset('cyborg/assets/images/logo.png')}}" alt="">
                    </a>
                    <!-- ***** Logo End ***** -->
                    
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li><a href="/" class="{{request()->is('/') ? 'active' : ''}}">Home</a></li>
                        <li><a href="/category/game-android" class="{{request()->is('category/game-android') ? 'active' : ''}}">Game Android</a></li>
                        <li><a href="/category/game-android-mod" class="{{request()->is('category/game-android-mod') ? 'active' : ''}}">Game Android Mod</a></li>
                        <li><a href="/category/game-pc" class="{{request()->is('category/game-pc') ? 'active' : ''}}">Game Pc</a></li>
                        <li><a href="/about-us" class="{{request()->is('about-us') ? 'active' : ''}}">About Us</a></li>
                        <li><a href="/profile" class="{{request()->is('profile') ? 'active' : ''}}">Profile <img src="{{asset('cyborg/assets/images/profile-header.jpg')}}" alt=""></a></li>
                    </ul>   
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-content">

          @yield('content')
        </div>
      </div>
    </div>
  </div>
  
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2023 - {{date('Y')}} <a href="http://ikhsannawawi.epizy.com">Mochammad Ikhsan Nawawi</a> . All rights reserved. 
          
        </div>
      </div>
    </div>
  </footer>


  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
  <script src="{{asset('cyborg/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('cyborg/vendor/bootstrap/js/bootstrap.min.js')}}"></script>

  <script src="{{asset('cyborg/assets/js/isotope.min.js')}}"></script>
  <script src="{{asset('cyborg/assets/js/owl-carousel.js')}}"></script>
  <script src="{{asset('cyborg/assets/js/tabs.js')}}"></script>
  <script src="{{asset('cyborg/assets/js/popup.js')}}"></script>
  <script src="{{asset('cyborg/assets/js/custom.js')}}"></script>

  @yield('script')
  </body>

</html>
