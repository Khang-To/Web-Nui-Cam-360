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

    @stack('styles')
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand text-decoration-none" href="#">
					<span class="align-middle">Mai Tùng House</span>
				</a>

				<ul class="sidebar-nav">
					<li class="sidebar-header text-info">
						HỆ THỐNG
					</li>

					<li class="sidebar-item">
                        <a class="sidebar-link" href="#">
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Bảng điều khiển</span>
						</a>
					</li>

                    <li class="sidebar-header text-info">
						NỘI DUNG GIỚI THIỆU
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="#">
							<i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Quản lý địa danh</span>
						</a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="#">
							<i class="align-middle" data-feather="camera"></i> <span class="align-middle">Quản lý điểm tương tác</span>
						</a>
					</li>

					<li class="sidebar-header text-info">
						QUẢN LÝ TOUR 360
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="#">
							<i class="align-middle" data-feather="image"></i> <span class="align-middle">Quản lý Cảnh 360</span>
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
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<span class="text-dark">
                                    {{ Auth::check() ? Auth::user()->name : 'Admin Mai Tùng' }}
								</span>
							</a>

							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="user"></i> Hồ sơ cá nhân</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Đăng xuất</a>
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

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0 mx-auto">
								<a class="text-muted" href="#"><strong>Mai Tùng House - Virtual Tour</strong></a>
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

    <script src="{{ asset('admin-assets/js/app.js') }}"></script>

    @stack('scripts')

</body>
</html>
