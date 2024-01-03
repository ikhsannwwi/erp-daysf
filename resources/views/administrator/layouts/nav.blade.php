<section class="section">
    <div class="card">
        <div class="card-body d-flex justify-content-between">
            <div class="nav-item">Panel Admin</div>
            <!-- Dropdown Notifikasi -->
            <div class="ml-auto d-flex">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle notification-icon" href="#" id="notificationDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right notification-dropdown"
                        aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item notification-text" href="#"><i class="fa fa-envelope"></i>
                                Notification 1</a></li>
                        <li><a class="dropdown-item notification-text" href="#"><i class="fa fa-bell"></i>
                                Notification 2</a></li>
                        <li><a class="dropdown-item notification-text" href="#"><i class="fa fa-users"></i>
                                Notification 3</a></li>
                    </ul>
                </div>

                <!-- Dropdown Pengguna -->
                <div class="nav-item dropdown ms-4">
                    <a class="nav-link dropdown-toggle user-icon" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-sm me-1">
                            <img src="@if (auth()->user() ? auth()->user()->profile && auth()->user()->profile->foto : '') {{ img_src(auth()->user()->profile->foto, 'profile') }}
            @else
                {{ asset('templateAdmin/assets/images/faces/2.jpg') }} @endif"
                                alt="" srcset="">
                        </div>
                        {{-- {{ dd(auth()->user()->name)  }} --}}
                        {{ auth()->user() ? auth()->user()->name : '' }}
                    </a>
                    <ul class="dropdown-menu user-dropdown" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item user-text"
                                href="{{ route('admin.profile', auth()->user() ? auth()->user()->kode : '') }}"><i
                                    class="fa fa-user-circle"></i> Profile</a></li>
                        <li><a class="dropdown-item user-text" href="{{ route('admin.settings') }}"><i
                                    class="fa fa-cog"></i> Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item user-text" href="{{ route('admin.logout') }}"><i
                                    class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
