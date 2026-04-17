<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống đang nâng cấp - {{ $globalSettings['website_name'] ?? 'Mai Tùng House' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .maintenance-card {
            background: #ffffff;
            border-radius: 20px;
            border-top: 6px solid #f39c12; /* Viền cam nổi bật */
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            padding: 3rem 2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Hiệu ứng hoạt hình cho cụm bánh răng */
        .gears-container {
            position: relative;
            height: 120px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        .gear-main {
            font-size: 5rem;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            animation: spin 6s linear infinite;
            color: #f39c12;
        }
        .gear-small-1 {
            font-size: 3rem;
            position: absolute;
            left: calc(50% + 40px);
            top: 50px;
            animation: spin-reverse 4s linear infinite;
        }
        .gear-small-2 {
            font-size: 2.5rem;
            position: absolute;
            left: calc(50% - 70px);
            top: 60px;
            animation: spin-reverse 5s linear infinite;
        }

        @keyframes spin { 100% { transform: translateX(-50%) rotate(360deg); } }
        @keyframes spin-reverse { 100% { transform: rotate(-360deg); } }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container text-center">

        <div class="mb-4">
            <img src="{{ !empty($globalSettings['logo']) ? asset('storage/' . $globalSettings['logo']) : asset('images/logo-default.png') }}"
                 alt="Logo" style="max-height: 80px; filter: grayscale(100%); opacity: 0.7;">
        </div>

        <div class="maintenance-card text-center">

            <div class="gears-container">
                <i class="fas fa-cog gear-main"></i>
                <i class="fas fa-cog gear-small-1"></i>
                <i class="fas fa-cog gear-small-2"></i>
            </div>

            <h2 class="fw-bold text-dark mb-3">Đang cập nhật tính năng</h2>

            <p class="text-secondary fs-5 mb-4">
                Chuyên mục <strong class="text-primary">{{ $moduleName ?? 'này' }}</strong> hiện đang được chúng tôi tạm khóa để tiến hành nâng cấp trải nghiệm.
                Vui lòng quay lại sau ít phút!
            </p>

            <hr class="bg-light my-4">

            <p class="text-muted mb-4">Nếu bạn cần hỗ trợ gấp, vui lòng liên hệ:</p>

            <a href="tel:{{ str_replace([' ', '.', '-'], '', $globalSettings['phone'] ?? '') }}" class="btn btn-warning btn-lg px-4 rounded-pill shadow-sm mb-3 me-2">
                <i class="fas fa-phone-alt me-2"></i> Gọi {{ $globalSettings['phone'] ?? 'Hotline' }}
            </a>

            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill shadow-sm mb-3">
                <i class="fas fa-home me-2"></i> Về Trang chủ
            </a>

        </div>
    </div>

</body>
</html>
