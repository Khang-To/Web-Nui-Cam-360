<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Mai Tùng House - Núi Cấm An Giang | Bình Yên Giữa Thiên Nhiên')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link href="{{ asset('client-assets/css/client-home.css') }}" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-mai-tung-house-nui-cam.png') }}" alt="Mai Tùng" class="logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                style="border: none;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                            href="{{ route('about') }}">Giới thiệu</a>
                    </li>
                    <li class="nav-item"><a
                            class="nav-link {{ request()->routeIs('client.location.*') ? 'active' : '' }}"
                            href="{{ route('client.location.index') }}">Danh thắng</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#360-tour">Tour 360°</a></li>

                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0 mb-3 mb-lg-0">
                        <a class="nav-social-icon" href="https://www.facebook.com/thaoduoctutam2288/" target="_blank"
                            title="Theo dõi Facebook Mai Tùng">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 text-center text-lg-start">
                    <h4 class="fw-bold fs-3 text-white mb-3 d-block">Mai Tùng</h4>
                    <p class="pe-lg-4">Điểm dừng chân bình yên, mang đến cho bạn không gian thư giãn tuyệt đối giữa đại
                        ngàn linh thiêng của Thiên Cấm Sơn.</p>
                </div>
                <div class="col-lg-4 mb-4 text-center text-lg-start">
                    <h5 class="fw-bold">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}">Về Mai Tùng</a></li>
                        <li class="mb-2"><a href="{{ route('client.location.index') }}">Danh thắng</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#360-tour">Trải nghiệm VR 360°</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4 text-center text-lg-start">
                    <h5 class="fw-bold">Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-warning"></i>Khu du lịch Núi Cấm,
                            Tịnh Biên, An Giang</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2 text-warning"></i>Hotline: 0123 456 789</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2 text-warning"></i>Email: contact@maitung.vn
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 opacity-25 border-secondary">
            <p class="text-center mb-0 small">© 2026 Mai Tùng House - Núi Cấm An Giang. Thiết kế và phát triển bởi Tô
                Văn Khang.</p>
        </div>
    </footer>

    <button id="backToTop" class="back-to-top" title="Lên đầu trang">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('client-assets/js/client-home.js') }}"></script>
</body>

</html>
