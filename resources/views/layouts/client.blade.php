<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Mai Tùng House - Núi Cấm An Giang | Bình Yên Giữa Thiên Nhiên')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <link href="{{ asset('client-assets/css/client-home.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">


    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ !empty($globalSettings['logo']) ? asset('storage/' . $globalSettings['logo']) : asset('images/logo-mai-tung-house-nui-cam.png') }}"
                    alt="Mai Tùng" class="logo-img">
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
                        <a class="nav-link {{ request()->routeIs('client.about') ? 'active' : '' }}"
                            href="{{ route('client.about') }}">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.location.*') ? 'active' : '' }}"
                            href="{{ route('client.location.index') }}">Điểm đến</a>
                    </li>
                    <li class="nav-item"><a
                            class="nav-link {{ request()->routeIs('client.virtualtour.*') ? 'active' : '' }}"
                            href="{{ route('client.virtualtour.index') }}">Tour 360°</a></li>

                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0 mb-3 mb-lg-0">
                        <a class="nav-social-icon" href="{{ $globalSettings['facebook'] ?? '#' }}" target="_blank"
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

    <div class="toast-container">

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="toast-item toast-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- VALIDATE ERROR --}}
        @if ($errors->any())
            <div class="toast-item toast-error">
                <i class="bi bi-x-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- CUSTOM ERROR --}}
        @if (session('error'))
            <div class="toast-item toast-error">
                <i class="bi bi-x-circle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- WARNING --}}
        @if (session('warning'))
            <div class="toast-item toast-warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 text-center text-lg-start">
                    <h4 class="fw-bold fs-3 text-white mb-3 d-block">Mai Tùng House - Núi Cấm</h4>
                    <p class="pe-lg-4">Điểm dừng chân bình yên, mang đến cho bạn không gian thư giãn tuyệt đối giữa đại
                        ngàn linh thiêng của Thiên Cấm Sơn.</p>
                </div>
                <div class="col-lg-4 mb-4 text-center text-lg-start">
                    <h5 class="fw-bold">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ route('client.about') }}">Về Mai Tùng</a></li>
                        <li class="mb-2"><a href="{{ route('client.location.index') }}">Điểm tham quan</a></li>
                        <li class="mb-2"><a href="{{ route('client.virtualtour.index') }}">Trải nghiệm VR 360°</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4 text-center text-lg-start">
                    <h5 class="fw-bold">Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                            {{ $globalSettings['address'] ?? 'Chưa cập nhật' }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone-alt me-2 text-warning"></i>Hotline:
                            <a href="tel:{{ str_replace([' ', '.', '-'], '', $globalSettings['phone'] ?? '') }}">
                                {{ $globalSettings['phone'] ?? 'Chưa cập nhật' }}
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-warning"></i>Email:
                            <a href="mailto:{{ $globalSettings['email'] ?? '' }}">
                                {{ $globalSettings['email'] ?? 'Chưa cập nhật' }}
                            </a>
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
    <script src="{{ asset('js/toast.js') }}"></script>

    @stack('scripts')
</body>

</html>
