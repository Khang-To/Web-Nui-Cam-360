@extends('layouts.client')

@section('title', 'Giới thiệu - Mai Tùng House Núi Cấm')

@section('content')

    @php
        // 1. Thiết lập hình nền mặc định
        $bgImage = '/images/hero-background/mai-tung-house-nui-cam-an-giang.jpg';

        // 2. Nếu Admin có up banner cho trang giới thiệu thì ghi đè đường dẫn
        if (isset($bannerAbout) && $bannerAbout) {
            $bgImage = asset('storage/' . $bannerAbout->image_path);
        }

    @endphp

    {{-- PHẦN HERO BANNER --}}
    <section class="d-flex align-items-center"
        style="height: 100%; min-height: 600px; background: linear-gradient(rgba(27, 94, 32, 0.7), rgba(0, 0, 0, 0.6)), url('{{ $bgImage }}') center/cover no-repeat fixed; margin-top: 0;">
        <div class="container text-center pt-5">
            <span class="text-uppercase fw-bold text-warning mb-2 d-block" style="letter-spacing: 2px;" data-aos="fade-down">Về
                Chúng Tôi</span>
            <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="100">Mai Tùng House</h1>
            <p class="lead text-light mx-auto px-3" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="200">
                Tổ hợp lưu trú, ẩm thực và trải nghiệm an yên giữa đại ngàn Thất Sơn linh thiêng.
            </p>
        </div>
    </section>

    {{-- PHẦN GIỚI THIỆU VỀ MAI TÙNG HOUSE --}}
    <section class="py-5 bg-white">
        <div class="container mt-4 mb-3">
            <div class="row align-items-center g-5">
                <div class="col-lg-5 text-center text-lg-start" data-aos="fade-right">
                    <h2 class="display-5 fw-bold mb-4">Chạm Vào Bình Yên</h2>
                    <p class="text-muted fs-5 mb-4">
                        Nằm nép mình ngay phía sau lưng <strong>Tượng Phật Di Lặc</strong> khổng lồ, Mai Tùng House trao gửi
                        một trải nghiệm chữa lành trọn vẹn.
                    </p>
                    <p class="text-muted">
                        Từ đây, bạn chỉ mất vài bước chân để tản bộ quanh Hồ Thủy Liêm sương giăng, nghe tiếng chuông Chùa
                        Vạn Linh ngân vang buổi sớm, hay tận hưởng cái se lạnh đặc trưng của "Đà Lạt miền Tây".
                    </p>
                </div>
                <div class="col-lg-6 offset-lg-1" data-aos="fade-left">
                    <div class="position-relative">
                        <img src="/images/about-image/nha-nghi-mai-tung.jpg" class="img-fluid rounded-4 shadow-sm w-100"
                            alt="Tượng Phật Di Lặc Núi Cấm">
                        <div
                            class="position-absolute bottom-0 start-50 translate-middle-x translate-middle-y bg-white px-4 py-3 rounded-pill shadow-lg text-nowrap">
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

    {{-- PHẦN GIỚI THIỆU DỊCH VỤ NỔI BẬT --}}
    <section class="py-5 py-lg-7 bg-light overflow-hidden">
        <div class="container">

            <div class="text-center mb-5 pb-4" data-aos="fade-up">
                <span class="text-uppercase fw-bold" style="color: var(--accent); letter-spacing: 2px;">Khám Phá Trải
                    Nghiệm</span>
                <h2 class="display-4 fw-bold mt-2">Hệ Sinh Thái Mai Tùng</h2>
            </div>

            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="position-relative img-hover-wrap pe-lg-4">
                        <img src="/images/about-image/phong-nghi-6 nguoi.jpg" class="service-image-modern"
                            alt="Nhà nghỉ Mai Tùng">
                        <div class="floating-icon"><i class="fas fa-bed"></i></div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 text-center text-lg-start" data-aos="fade-left">
                    <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Nhà Nghỉ Lưu Trú</h3>
                    <p class="text-muted fs-6 mb-4">Hệ thống phòng ốc đa dạng (phòng đôi, phòng gia đình, phòng tập thể)
                        được thiết kế sạch sẽ, ấm cúng. Trạm dừng chân lý tưởng sau một ngày dài leo núi rã rời.</p>
                    <ul class="list-unstyled text-muted text-start d-inline-block">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Cửa sổ view núi thoáng đãng
                        </li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Hệ thống máy nước nóng 24/7
                        </li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Miễn phí Wi-Fi tốc độ cao
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-5 order-2 order-lg-1 text-center text-lg-start" data-aos="fade-right">
                    <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Nhà Hàng Ẩm Thực</h3>
                    <p class="text-muted fs-6 mb-4">Thưởng thức tinh hoa ẩm thực Thất Sơn ngay tại Mai Tùng với nguyên liệu
                        tươi ngon được thu hoạch trực tiếp từ bản địa, mang đậm hương vị miền núi.</p>
                    <ul class="list-unstyled text-muted text-start d-inline-block">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Bánh xèo núi kèm 20 loại rau
                            rừng</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Cua núi rang me & Gà chạy bộ
                            nướng</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Nhận setup tiệc gia đình,
                            BBQ ngoài trời</li>
                    </ul>
                </div>
                <div class="col-lg-6 offset-lg-1 order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                    <div class="position-relative img-hover-wrap ps-lg-4">
                        <img src="/images/about-image/nha-hang-mai-tung.jpg" class="service-image-modern"
                            alt="Nhà hàng Mai Tùng">
                        <div class="floating-icon left-icon"><i class="fas fa-utensils"></i></div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="position-relative img-hover-wrap pe-lg-4">
                        <img src="/images/about-image/camping-mai-tung.jpg" class="service-image-modern"
                            style="filter: brightness(0.85);" alt="Cắm trại ngoài trời">
                        <div class="floating-icon"><i class="fas fa-campground"></i></div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 text-center text-lg-start" data-aos="fade-left">
                    <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Trại Ngoài Trời</h3>
                    <p class="text-muted fs-6 mb-4">Dành cho những tâm hồn yêu tự do. Cùng bạn bè quây quần bên bếp lửa,
                        đánh đàn guitar và ngắm bầu trời sao đêm tuyệt đẹp giữa cái se lạnh của đỉnh núi cao nhất miền Tây.
                    </p>
                    <ul class="list-unstyled text-muted text-start d-inline-block">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Cho thuê lều trại cao cấp,
                            cách nhiệt</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Bếp nướng lửa trại & củi
                            khô chuẩn bị sẵn</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Khu vực an toàn, có nhà vệ
                            sinh sạch sẽ gần kề</li>
                    </ul>
                </div>
            </div>

            <div class="row align-items-center mb-2">
                <div class="col-lg-5 order-2 order-lg-1 text-center text-lg-start" data-aos="fade-right">
                    <h3 class="display-6 fw-bold mb-4" style="color: var(--primary);">Tour Khám Phá & VR 360°</h3>
                    <p class="text-muted fs-6 mb-4">Mai Tùng không chỉ cung cấp chỗ ở mà còn là những "thổ địa" chính hiệu,
                        sẵn sàng đồng hành cùng bạn trên mọi nẻo đường khám phá.</p>
                    <ul class="list-unstyled text-muted mb-4 text-start d-inline-block">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Tư vấn cung đường
                            trekking, hang động an toàn</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Hỗ trợ gọi xe ôm, mua vé
                            cáp treo nhanh chóng</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Trải nghiệm công nghệ
                            <strong>Thực Tế Ảo (VR 360°)</strong> độc quyền
                        </li>
                    </ul>
                    <div class="d-block mt-2">
                        <a href="{{ route('client.virtualtour.index') }}"
                            class="btn btn-outline-success rounded-pill px-4 fw-bold w-100 w-sm-auto">Trải nghiệm VR 360°
                            Ngay <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                    <div class="position-relative img-hover-wrap ps-lg-4">
                        <img src="/images/about-image/tour-kham-pha-nui-cam.jpg" class="service-image-modern"
                            alt="Tour tham quan Núi Cấm">
                        <div class="floating-icon left-icon"><i class="fas fa-route"></i></div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- CẢM NHẬN KHÁCH HÀNG --}}
    <section class="py-5 bg-white border-top border-bottom">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-uppercase fw-bold text-success" style="letter-spacing: 2px;">Cảm Nhận</span>
                <h2 class="display-5 fw-bold mt-2">Góc Nhìn Khách Hàng</h2>
                <p class="text-muted">Những câu chuyện chữa lành được viết lại sau mỗi chuyến đi.</p>
            </div>

            <div class="row gx-5">

                {{-- CỘT TRÁI: DANH SÁCH ĐÁNH GIÁ (Cuộn dọc) --}}
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <h4 class="fw-bold mb-4 border-bottom pb-2">Đánh giá nổi bật</h4>

                    <div id="reviewsContainer" class="pe-3"
                        style="max-height: 500px; overflow-y: auto; scrollbar-width: thin;">
                        {{-- Laravel Render các Đánh giá đã duyệt ở đây --}}
                        @forelse($approvedReviews ?? [] as $rev)
                            <div class="review-item mb-4 p-4 rounded bg-light border-start border-4 border-success">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">
                                        {{ $rev->name }}
                                        <span class="text-muted small fw-normal ms-1">({{ $rev->masked_email }})</span>
                                    </h6>
                                    <small class="text-muted">{{ $rev->created_at->format('d/m/Y') }}</small>
                                </div>

                                <div class="text-warning mb-2 fs-6">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }} me-1"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 text-dark fst-italic text-break" style="white-space: pre-line;">
                                    "{{ $rev->content }}"</p>
                            </div>
                        @empty
                            <div class="text-center text-muted p-5 bg-light rounded">
                                <i class="fas fa-comment-dots fs-1 mb-3 opacity-50"></i>
                                <p class="mb-0">Chưa có đánh giá nào. Hãy là người đầu tiên chia sẻ cảm nhận!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- CỘT PHẢI: FORM VIẾT ĐÁNH GIÁ --}}
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-4">Để lại đánh giá của bạn</h4>

                            <form id="reviewForm">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Tên của bạn <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="VD: Khang Tô" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Sẽ được ẩn đi (VD: k***g@...)" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Mức độ hài lòng <span
                                            class="text-danger">*</span></label>
                                    <select name="rating" class="form-select text-warning fw-bold" required>
                                        <option value="5" selected>⭐⭐⭐⭐⭐ - Tuyệt vời</option>
                                        <option value="4">⭐⭐⭐⭐ - Rất tốt</option>
                                        <option value="3">⭐⭐⭐ - Bình thường</option>
                                        <option value="2">⭐⭐ - Tạm được</option>
                                        <option value="1">⭐ - Rất tệ</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-semibold">Chia sẻ trải nghiệm của bạn <span
                                            class="text-danger">*</span></label>
                                    <textarea name="content" class="form-control" rows="4"
                                        placeholder="Bạn cảm thấy thế nào về dịch vụ, phòng ốc, đồ ăn...?" required></textarea>
                                </div>

                                <button type="submit" id="btnSubmitReview"
                                    class="btn btn-success w-100 py-2 fw-bold text-uppercase"
                                    style="letter-spacing: 1px;">
                                    <i class="fas fa-paper-plane me-2"></i> Gửi Đánh Giá
                                </button>
                                <div class="text-center mt-3">
                                    <small class="text-muted"><i class="fas fa-shield-alt me-1 text-success"></i> Email
                                        của bạn sẽ được bảo mật tuyệt đối.</small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- CALL TO ACTION --}}
    <section class="py-5" style="background-color: var(--primary);">
        <div class="container text-center py-5">
            <h2 class="display-6 fw-bold text-white mb-4" data-aos="zoom-in">Bạn đã sẵn sàng cho chuyến đi chữa lành?</h2>
            <p class="lead text-light mb-5 opacity-75 mx-auto" style="max-width: 700px;" data-aos="zoom-in"
                data-aos-delay="100">
                Liên hệ ngay với chúng tôi để kiểm tra phòng trống và nhận báo giá tốt nhất cho kỳ nghỉ của bạn.
            </p>
            <div data-aos="zoom-in" data-aos-delay="200">
                <a href="https://m.me/thaoduoctutam2288" target="_blank"
                    class="btn btn-accent btn-lg me-sm-3 mb-3 mb-sm-0 px-4">
                    <i class="fab fa-facebook-messenger me-2"></i>Nhắn tin đặt phòng
                </a>

                <a href="tel:{{ str_replace([' ', '.', '-'], '', $globalSettings['phone'] ?? '0123456789') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="fas fa-phone-alt me-2"></i>Gọi Hotline
                </a>
            </div>
        </div>
    </section>
@endsection

{{-- Script xử lý gửi đánh giá bằng AJAX --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reviewForm = document.getElementById('reviewForm');

            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Chặn load lại trang

                    const btnSubmit = document.getElementById('btnSubmitReview');
                    const originalBtnText = btnSubmit.innerHTML;

                    // Đổi trạng thái nút thành Loading
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span> Đang gửi...';

                    const formData = new FormData(this);

                    fetch('{{ route('client.reviews.store') }}', {
                            method: 'POST',
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                "Accept": "application/json"
                            },
                            body: formData
                        })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) throw data;
                            return data;
                        })
                        .then(data => {
                            // 1. Dùng thư viện Toast (hoặc Alert mặc định) báo thành công
                            if (typeof showToast === 'function') {
                                showToast(data.message, "success");
                            } else {
                                alert(data.message);
                            }

                            // 2. Clear Form
                            reviewForm.reset();

                            // 3. (Tùy chọn) Chèn trực tiếp đánh giá mới lên đầu danh sách nết is_approved = true
                            // Do Admin set default(false) nên ta có thể chỉ cần báo: "Đã gửi, chờ duyệt" là đủ.
                        })
                        .catch(err => {
                            let errorMsg = err.message || "Lỗi mạng, không thể gửi đánh giá!";
                            if (err.errors) errorMsg = Object.values(err.errors)[0][0]; // Lỗi Validate

                            if (typeof showToast === 'function') {
                                showToast(errorMsg, "error");
                            } else {
                                alert(errorMsg);
                            }
                        })
                        .finally(() => {
                            // Trả lại nút như cũ
                            btnSubmit.disabled = false;
                            btnSubmit.innerHTML = originalBtnText;
                        });
                });
            }
        });
    </script>
@endpush
