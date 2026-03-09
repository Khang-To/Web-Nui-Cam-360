<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Trang quản trị - Núi Cấm 360')</title>

    <link href="{{ asset('admin-assets/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/toast.css') }}">

    @stack('styles')
    <style>
        .footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 1rem 0;
            width: 100%;
            box-sizing: border-box;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand text-decoration-none" href="{{ route('admin.dashboard') }}">
                    <span class="align-middle">Núi Cấm 360</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header text-info">
                        HỆ THỐNG
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                            <i class="align-middle me-2" data-feather="pie-chart"></i>
                            <span class="align-middle">Bảng điều khiển</span>
                        </a>
                    </li>

                    <li class="sidebar-header text-info">
                        NỘI DUNG GIỚI THIỆU
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.locations.index') }}">
                            <i class="align-middle me-2" data-feather="map-pin"></i>
                            <span class="align-middle">Quản lý địa điểm du lịch</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.tourist_objects.index') }}">
                            <i class="align-middle me-2 bi bi-star-fill"></i>
                            <span class="align-middle">Quản lý đối tượng tham quan</span>
                        </a>
                    </li>

                    <li class="sidebar-header text-info">
                        QUẢN LÝ TOUR 360
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#">
                            <i class="align-middle me-2" data-feather="aperture"></i>
                            <span class="align-middle">Cấu hình tour 360</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
                                data-bs-toggle="dropdown">
                                <span class="text-dark">
                                    {{ Auth::user()->username }}
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-house me-2"></i> Bảng điều khiển
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.change-password') }}">
                                    <i class="bi bi-key me-2"></i> Đổi mật khẩu
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            @yield('content')
                        </div>
                    </div>
                </div>
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

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0 mx-auto">
                                <a class="text-muted" href="#">
                                    <strong>Mai Tùng House - Virtual Tour</strong>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('admin-assets/js/app.js') }}"></script>
    <script src="{{ asset('admin-assets/js/toast.js') }}"></script>

    @stack('scripts')

</body>

</html>
