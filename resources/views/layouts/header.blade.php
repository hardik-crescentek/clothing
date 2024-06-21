<header class="header">
    <nav class="navbar fixed-top">
        <!-- Begin Search Box-->
        <div class="search-box">
            <button class="dismiss"><i class="ion-close-round"></i></button>
            <form id="searchForm" action="#" role="search">
                <input type="search" placeholder="Search something ..." class="form-control">
            </form>
        </div>
        <!-- End Search Box-->
        <!-- Begin Topbar -->
        <div class="navbar-holder d-flex align-items-center align-middle justify-content-between">
            <!-- Begin Logo -->
            <div class="navbar-header">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <div class="brand-image brand-big">
                        <img src="{{ asset('assets/img/logo-big.png') }}" alt="logo" class="logo-big">
                    </div>
                    <div class="brand-image brand-small">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="logo-small">
                    </div>
                </a>
                <!-- Toggle Button -->
                <a id="toggle-btn" href="#" class="menu-btn active">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
                <!-- End Toggle -->
            </div>
            <!-- End Logo -->
            <!-- Begin Navbar Menu -->

            <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center pull-right">

                <!-- POS -->
                @auth
                <li class="nav-item d-flex align-items-center mr-3"><a id="pos" href="{{ route('pos') }}">POS</a></li>
                @endauth
                <!-- Search -->
                <li class="nav-item d-flex align-items-center"><a id="search" href="#"><i class="la la-search"></i></a></li>
                <!-- End Search -->


                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endif
                @else
                <!-- Begin Notifications -->
                <li class="nav-item dropdown"><a id="notifications" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="la la-bell animated infinite swing"></i><span class="badge-pulse"></span></a>
                    <ul aria-labelledby="notifications" class="dropdown-menu notification">
                        <div id="Notidata"></div>
                        <li>
                            <a rel="nofollow" href="{{ route('header.notifaction') }}" class="dropdown-item all-notifications text-center">View All Notifications</a>
                        </li>
                    </ul>
                </li>
                <!-- End Notifications -->
                <!-- User -->
                <li class="nav-item dropdown">
                    <a id="user" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link">
                        <img src="{{ img_url(Auth::user()->avatar) }}" alt="..." class="avatar rounded-circle">
                    </a>
                    <ul aria-labelledby="user" class="user-size dropdown-menu">
                        <li class="welcome">
                            <a href="{{ route('profile') }}" class="edit-profil"><i class="la la-gear"></i></a>
                            <img src="{{ img_url(Auth::user()->avatar) }}" alt="..." class="rounded-circle">
                        </li>
                        <li class="text-center">
                            <a href="{{ route('profile') }}" class="dropdown-item mt-3">
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="text-center">
                            <a href="{{ route('logout') }}" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ti-power-off"></i></a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </li>
                    </ul>
                </li>
                <!-- End User -->
                @endguest

                <!-- Begin Quick Actions -->
                <!-- <li class="nav-item"><a href="#off-canvas" class="open-sidebar"><i class="la la-ellipsis-h"></i></a></li> -->
                <!-- End Quick Actions -->
            </ul>
            <!-- End Navbar Menu -->
        </div>
        <!-- End Topbar -->
    </nav>
</header>
