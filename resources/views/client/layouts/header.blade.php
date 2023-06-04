<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

        <h1 class="logo me-auto"><a href="{{ route('client.index') }}">{{ config('app.name') }}</a></h1>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero">Trang chủ</a></li>
                <li><a class="nav-link scrollto" href="#services">Dịch vụ</a></li>
                <li><a class="nav-link scrollto" href="#portfolio">Sân bóng</a></li>
                <li><a class="nav-link scrollto" href="#about">Về chúng tôi</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)"><span>Chức năng</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="#" data-bs-toggle="modal"
                                data-bs-target="#findFootballPitchAvailableModal">Tìm kiếm sân trống</a></li>
                        <li><a href="{{ route('client.findOrderByCode') }}">Tra cứu yêu cầu đặt sân</a></li>
                    </ul>
                </li>
                @guest
                    <li><a class="getstarted" href="{{ route('client.login') }}">Đăng nhập</a></li>
                    <li><a class="getstarted" href="{{ route('client.register') }}">Đăng ký</a></li>
                @endguest
                @auth
                    <li class="dropdown">
                        <a class="getstarted" data-bs-toggle="dropdown" href="#">Tài khoản</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header text-center">
                                <h6 class="fw-bold">{{ auth()->user()->name }}</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @if (auth()->user()->role != 0)
                            <li>
                                <a class="dropdown-item d-flex justify-content-start align-items-center text-left"
                                    href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-bar-chart-line-fill"></i>
                                    <span>Đi đến trang quản trị</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item d-flex justify-content-start align-items-center text-left"
                                    href="{{ route('client.profile') }}">
                                    <i class="bi bi-person"></i>
                                    <span>Thông tin</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item justify-content-start d-flex align-items-center"
                                    href="{{ route('client.orderByMe') }}">
                                    <i class="bi bi-card-list"></i>
                                    <span>Yêu cầu đã đặt</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item justify-content-start d-flex align-items-center"
                                    href="{{ route('client.logout') }}">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Đăng xuất</span>
                                </a>
                            </li>

                        </ul><!-- End Profile Dropdown Items -->
                    </li>
                @endauth
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->
@include('client.modal.findFootballPitchAvailable')
@include('client.modal.orderWhenFoundFootballPitch')
