<header class="mb-5">
    <div class="header-top">
        <div class="container">
            <div class="logo">
                <a href="index.html"><img src="{{ array_key_exists('logo_app_admin', $settings) ? img_src($settings['logo_app_admin'], 'settings') : '' }}" alt=" "></a>
            </div>
            <div class="header-top-right">

                <div class="dropdown">
                    <a href="#" id="topbarUserDropdown"
                        class="user-dropdown d-flex align-items-center dropend dropdown-toggle "
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-md2">
                            <img src="@if (auth()->user() ? auth()->user()->profile && auth()->user()->profile->foto : '') {{ img_src(auth()->user()->profile->foto, 'profile') }}
            @else
                {{ asset('templateAdmin/assets/images/faces/2.jpg') }} @endif"
                                alt="" srcset="">
                        </div>
                        <div class="text">
                            <h6 class="user-dropdown-name">
                                {{ auth()->guard('user_member')->user() ? auth()->guard('user_member')->user()->name : (auth()->user() ? auth()->user()->name : '') }}
                            </h6>
                            {{-- <p class="user-dropdown-status text-sm text-muted">Member</p> --}}
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                        aria-labelledby="topbarUserDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.profile', auth()->guard('user_member')->user() ? auth()->guard('user_member')->user()->kode : '') }}">My Account</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('member.logout') }}">Logout</a></li>
                    </ul>
                </div>

                <!-- Burger button responsive -->
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </div>
        </div>
    </div>
    
    @include('member.layouts.headbar')

</header>