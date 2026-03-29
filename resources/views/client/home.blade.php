@extends('layouts.client')

@section('title', 'Mai Tùng House - Núi Cấm An Giang | Trang Chủ')

@section('content')

    @php
        // Thiết lập hình nền mặc định cho trang chủ
        $heroBg = '/images/hero-background/banner-khu-du-lich-nui-cam.jpg';

        // Ghi đè hình nền nếu admin có up Banner
        if(isset($bannerHome) && $bannerHome) {
            $heroBg = asset('storage/' . $bannerHome->image_path);
        }
    @endphp

    <section id="home" class="hero d-flex align-items-center" style="background: linear-gradient(rgba(27, 94, 32, 0.6), rgba(0, 0, 0, 0.7)), url('{{ $heroBg }}') center/cover no-repeat fixed;">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-4" data-aos="fade-up">Bình Yên Giữa Đại Ngàn Thiên Cấm Sơn</h1>
            <p class="lead fs-5 mb-5 mx-auto" style="max-width: 700px;" data-aos="fade-up" data-aos-delay="200">
                Khám phá vẻ đẹp hùng vĩ, mây núi trập trùng và không gian tâm linh huyền bí tại đỉnh núi cao nhất miền Tây
                Nam Bộ.
            </p>
            <div class="d-flex gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="400">
                <a href="#danh-thang" class="btn btn-accent">Khám phá ngay</a>
                <a href="{{ route('client.virtualtour.index') }}" class="btn btn-outline-light"><i class="fas fa-vr-cardboard me-2"></i>Xem Tour 360°</a>
            </div>
        </div>
    </section>

    <section id="gioi-thieu" class="py-5 py-lg-7">
        <div class="container mt-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="text-uppercase fw-bold text-muted letter-spacing-1">Về chúng tôi</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Thiên Cấm Sơn Huyền Thoại</h2>
                    <p class="text-muted mb-4 fs-5">
                        Núi Cấm (Thiên Cấm Sơn) tọa lạc tại huyện Tịnh Biên, An Giang, tự hào là ngọn núi cao nhất khu vực
                        Đồng bằng sông Cửu Long. Nơi đây được thiên nhiên ưu ái ban tặng thảm rừng nguyên sinh xanh ngát và
                        khí hậu mát mẻ tựa Đà Lạt thu nhỏ.
                    </p>
                    <p class="text-muted mb-4">
                        <strong>Mai Tùng</strong> sở hữu vị trí đắc địa ngay phía sau tượng Phật Di Lặc. Từ đây, du khách có
                        thể thu trọn vào tầm mắt khung cảnh núi rừng bao la, hồ Thủy Liêm tĩnh lặng và dễ dàng di chuyển đến
                        các điểm tham quan cốt lõi của khu du lịch.
                    </p>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="rounded-4 overflow-hidden shadow-lg border border-3 border-white">
                        <div class="ratio ratio-16x9 bg-dark">
                            <iframe id="flycam-video" src=""
                                data-src="https://www.youtube.com/embed/NABivpBPqck?autoplay=1&mute=1"
                                title="Flycam Toàn Cảnh Núi Cấm"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                        <div class="d-flex align-items-center bg-white px-3 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-mountain text-primary me-2"></i>
                            <span class="fw-bold fs-5 text-dark">716m</span>
                            <span class="text-muted small ms-2 border-start ps-2">Đỉnh núi cao nhất miền Tây</span>
                        </div>
                        <span class="text-muted small fst-italic"><i class="fab fa-youtube text-danger me-1"></i>Video toàn
                            cảnh</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="360-tour" class="py-5">
        <div class="container">
            <div class="tour-360-section shadow-lg" data-aos="zoom-in">
                <i class="fas fa-globe tour-360-icon"></i>
                <div class="row align-items-center relative z-index-1">
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <h2 class="display-6 fw-bold mb-3">Khám Phá Núi Cấm Qua Góc Nhìn 360°</h2>
                        <p class="lead mb-0 opacity-75">Trải nghiệm không gian chân thực của Tượng Phật Di Lặc, Hồ Thủy Liêm
                            và Chùa Vạn Linh ngay tại nhà với công nghệ thực tế ảo tương tác toàn cảnh.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('client.virtualtour.index') }}" class="btn btn-light btn-lg text-success fw-bold px-4 rounded-pill shadow">
                            Trải Nghiệm Ngay <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="danh-thang" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-uppercase fw-bold" style="color: var(--accent);">Điểm đến</span>
                <h2 class="display-5 fw-bold mt-2">Danh Thắng Nổi Bật</h2>
            </div>
            <div class="row g-4">
                @foreach ($danhThangs as $key => $item)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $key * 100 }}">

                        <a href="{{ route('client.location.detail', $item->id) }}" class="text-decoration-none">
                            <div class="card h-100">
                                <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->name }}">

                                <div class="card-body p-4 text-center">
                                    <h4 class="text-dark">{{ $item->name }}</h4>
                                    <p class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($item->short_description, 100) }}</p>

                                    <span class="text-success fw-bold small mt-2 d-inline-block">
                                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">

            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-uppercase fw-bold" style="color: var(--accent);">Thư viện</span>
                <h2 class="display-5 fw-bold mt-2">Vẻ Đẹp Thiên Nhiên Núi Cấm</h2>
            </div>

            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
                <div class="col-lg-10">

                    @if (isset($carousels) && $carousels->count() > 0)
                        <div id="galleryCarousel" class="carousel slide shadow-lg rounded-4 overflow-hidden"
                            data-bs-ride="carousel" data-bs-interval="4000">

                            <div class="carousel-indicators">
                                @foreach ($carousels as $index => $slider)
                                    <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="{{ $index }}"
                                        class="{{ $index == 0 ? 'active' : '' }}"></button>
                                @endforeach
                            </div>

                            <div class="carousel-inner">
                                @foreach ($carousels as $index => $slider)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $slider->image_path) }}" class="d-block w-100"
                                            alt="{{ $slider->title ?? 'Hình ảnh Núi Cấm' }}">

                                        @if ($slider->title || $slider->description)
                                            <div class="carousel-caption d-none d-md-block">
                                                @if ($slider->title)
                                                    <h5 class="fw-bold text-white mb-1 fs-4">{{ $slider->title }}</h5>
                                                @endif
                                                @if ($slider->description)
                                                    <p class="text-light mb-0">{{ $slider->description }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"
                                    style="filter: drop-shadow(0 0 5px rgba(0,0,0,0.8));"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"
                                    style="filter: drop-shadow(0 0 5px rgba(0,0,0,0.8));"></span>
                                <span class="visually-hidden">Next</span>
                            </button>

                        </div>
                    @else
                        <div class="text-center p-5 bg-light rounded-4 border">
                            <p class="text-muted mb-0">Hình ảnh đang được cập nhật...</p>
                        </div>
                    @endif
                    </div>
            </div>
        </div>
    </section>

    <section class="py-5 text-center bg-light">
        <div class="container">
            <h2 class="display-5 fw-bold mb-4">Bản Đồ Đường Đi</h2>
            <p class="text-muted mb-5 mx-auto" style="max-width: 800px;">Địa chỉ: Xã An Hảo, huyện Tịnh Biên, tỉnh An
                Giang. Chỉ cách trung tâm Châu Đốc khoảng 35km và thành phố Long Xuyên 90km. Đường lên núi hiện đã được trải
                nhựa rộng rãi, có dịch vụ xe cáp treo và xe ôm đưa rước.</p>
            <div class="ratio ratio-21x9 rounded-4 overflow-hidden shadow-lg" data-aos="zoom-in">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3728.824339396219!2d104.982946!3d10.506374699999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109f76b0eaaf8c1%3A0x4a05ad8de3eb6677!2zTWFpIFTDuW5nIEhvdXNlIC0gTsO6aSBD4bqlbQ!5e1!3m2!1svi!2s!4v1773219533638!5m2!1svi!2s"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
@endsection
