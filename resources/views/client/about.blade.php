@extends('layouts.client')

@section('title', 'Giới thiệu - Mai Tùng House Núi Cấm')

@section('content')
<section class="d-flex align-items-center" style="height: 50vh; min-height: 400px; background: linear-gradient(rgba(27, 94, 32, 0.7), rgba(0, 0, 0, 0.6)), url('/images/hero-background/mai-tung-house-nui-cam-an-giang.jpg') center/cover no-repeat fixed; margin-top: 0;">
  <div class="container text-center pt-5">
    <span class="text-uppercase fw-bold text-warning mb-2 d-block" style="letter-spacing: 2px;" data-aos="fade-down">Về Chúng Tôi</span>
    <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="100">Mai Tùng House</h1>
    <p class="lead text-light mx-auto px-3" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="200">
      Tổ hợp lưu trú, ẩm thực và trải nghiệm an yên giữa đại ngàn Thất Sơn linh thiêng.
    </p>
  </div>
</section>

<section class="py-5 bg-white">
  <div class="container mt-4 mb-3">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 text-center text-lg-start" data-aos="fade-right">
        <h2 class="display-5 fw-bold mb-4">Chạm Vào Bình Yên</h2>
        <p class="text-muted fs-5 mb-4">
          Nằm nép mình ngay phía sau lưng <strong>Tượng Phật Di Lặc</strong> khổng lồ, Mai Tùng House trao gửi một trải nghiệm chữa lành trọn vẹn.
        </p>
        <p class="text-muted">
          Từ đây, bạn chỉ mất vài bước chân để tản bộ quanh Hồ Thủy Liêm sương giăng, nghe tiếng chuông Chùa Vạn Linh ngân vang buổi sớm, hay tận hưởng cái se lạnh đặc trưng của "Đà Lạt miền Tây".
        </p>
      </div>
      <div class="col-lg-6 offset-lg-1" data-aos="fade-left">
        <div class="position-relative">
          <img src="/images/about-image/nha-nghi-mai-tung.jpg" class="img-fluid rounded-4 shadow-sm w-100" alt="Tượng Phật Di Lặc Núi Cấm">
          <div class="position-absolute bottom-0 start-50 translate-middle-x translate-middle-y bg-white px-4 py-3 rounded-pill shadow-lg text-nowrap">
            <i class="fas fa-star text-warning me-1"></i>
            <i class="fas fa-star text-warning me-1"></i>
            <i class="fas fa-star text-warning me-1"></i>
            <i class="fas fa-star text-warning me-1"></i>
            <i class="fas fa-star text-warning me-2"></i>
            <span class="fw-bold text-dark d-none d-sm-inline">Trải nghiệm xuất sắc</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 py-lg-7 bg-light overflow-hidden">
  <div class="container">

    <div class="text-center mb-5 pb-4" data-aos="fade-up">
      <span class="text-uppercase fw-bold" style="color: var(--accent); letter-spacing: 2px;">Khám Phá Trải Nghiệm</span>
      <h2 class="display-4 fw-bold mt-2">Hệ Sinh Thái Mai Tùng</h2>
    </div>

    <div class="row align-items-center mb-5 pb-5">
      <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
        <div class="position-relative img-hover-wrap pe-lg-4">
          <img src="/images/about-image/phong-nghi-6 nguoi.jpg" class="service-image-modern" alt="Nhà nghỉ Mai Tùng">
          <div class="floating-icon"><i class="fas fa-bed"></i></div>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1 text-center text-lg-start" data-aos="fade-left">
        <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Nhà Nghỉ Lưu Trú</h3>
        <p class="text-muted fs-6 mb-4">Hệ thống phòng ốc đa dạng (phòng đôi, phòng gia đình, phòng tập thể) được thiết kế sạch sẽ, ấm cúng. Trạm dừng chân lý tưởng sau một ngày dài leo núi rã rời.</p>
        <ul class="list-unstyled text-muted text-start d-inline-block">
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Cửa sổ view núi thoáng đãng</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Hệ thống máy nước nóng 24/7</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Miễn phí Wi-Fi tốc độ cao</li>
        </ul>
      </div>
    </div>

    <div class="row align-items-center mb-5 pb-5">
      <div class="col-lg-5 order-2 order-lg-1 text-center text-lg-start" data-aos="fade-right">
        <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Nhà Hàng Ẩm Thực</h3>
        <p class="text-muted fs-6 mb-4">Thưởng thức tinh hoa ẩm thực Thất Sơn ngay tại Mai Tùng với nguyên liệu tươi ngon được thu hoạch trực tiếp từ bản địa, mang đậm hương vị miền núi.</p>
        <ul class="list-unstyled text-muted text-start d-inline-block">
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Bánh xèo núi kèm 20 loại rau rừng</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Cua núi rang me & Gà chạy bộ nướng</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Nhận setup tiệc gia đình, BBQ ngoài trời</li>
        </ul>
      </div>
      <div class="col-lg-6 offset-lg-1 order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
        <div class="position-relative img-hover-wrap ps-lg-4">
          <img src="/images/about-image/nha-hang-mai-tung.jpg" class="service-image-modern" alt="Nhà hàng Mai Tùng">
          <div class="floating-icon left-icon"><i class="fas fa-utensils"></i></div>
        </div>
      </div>
    </div>

    <div class="row align-items-center mb-5 pb-5">
      <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
        <div class="position-relative img-hover-wrap pe-lg-4">
          <img src="/images/about-image/camping-mai-tung.jpg" class="service-image-modern" style="filter: brightness(0.85);" alt="Cắm trại ngoài trời">
          <div class="floating-icon"><i class="fas fa-campground"></i></div>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1 text-center text-lg-start" data-aos="fade-left">
        <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Trại Ngoài Trời</h3>
        <p class="text-muted fs-6 mb-4">Dành cho những tâm hồn yêu tự do. Cùng bạn bè quây quần bên bếp lửa, đánh đàn guitar và ngắm bầu trời sao đêm tuyệt đẹp giữa cái se lạnh của đỉnh núi cao nhất miền Tây.</p>
        <ul class="list-unstyled text-muted text-start d-inline-block">
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Cho thuê lều trại cao cấp, cách nhiệt</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Bếp nướng lửa trại & củi khô chuẩn bị sẵn</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Khu vực an toàn, có nhà vệ sinh sạch sẽ gần kề</li>
        </ul>
      </div>
    </div>

    <div class="row align-items-center mb-2">
      <div class="col-lg-5 order-2 order-lg-1 text-center text-lg-start" data-aos="fade-right">
        <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Tour Khám Phá & VR 360°</h3>
        <p class="text-muted fs-6 mb-4">Mai Tùng không chỉ cung cấp chỗ ở mà còn là những "thổ địa" chính hiệu, sẵn sàng đồng hành cùng bạn trên mọi nẻo đường khám phá.</p>
        <ul class="list-unstyled text-muted mb-4 text-start d-inline-block">
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Tư vấn cung đường trekking, hang động an toàn</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Hỗ trợ gọi xe ôm, mua vé cáp treo nhanh chóng</li>
          <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Trải nghiệm công nghệ <strong>Thực Tế Ảo (VR 360°)</strong> độc quyền</li>
        </ul>
        <div class="d-block mt-2">
            <a href="{{ route('home') }}#360-tour" class="btn btn-outline-success rounded-pill px-4 fw-bold w-100 w-sm-auto">Trải nghiệm VR 360° Ngay <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
      </div>
      <div class="col-lg-6 offset-lg-1 order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
        <div class="position-relative img-hover-wrap ps-lg-4">
          <img src="/images/about-image/tour-kham-pha-nui-cam.jpg" class="service-image-modern" alt="Tour tham quan Núi Cấm">
          <div class="floating-icon left-icon"><i class="fas fa-route"></i></div>
        </div>
      </div>
    </div>

  </div>
</section>

<section class="py-5" style="background-color: var(--primary);">
  <div class="container text-center py-5">
    <h2 class="display-6 fw-bold text-white mb-4" data-aos="zoom-in">Bạn đã sẵn sàng cho chuyến đi chữa lành?</h2>
    <p class="lead text-light mb-5 opacity-75 mx-auto" style="max-width: 700px;" data-aos="zoom-in" data-aos-delay="100">
      Liên hệ ngay với chúng tôi để kiểm tra phòng trống và nhận báo giá tốt nhất cho kỳ nghỉ của bạn.
    </p>
    <div data-aos="zoom-in" data-aos-delay="200">
      <a href="https://www.facebook.com/maitungnuicam" target="_blank" class="btn btn-accent btn-lg me-sm-3 mb-3 mb-sm-0 px-4">
        <i class="fab fa-facebook-messenger me-2"></i>Nhắn tin đặt phòng
      </a>
      <a href="tel:0123456789" class="btn btn-outline-light btn-lg px-4">
        <i class="fas fa-phone-alt me-2"></i>Gọi Hotline
      </a>
    </div>
  </div>
</section>
@endsection
