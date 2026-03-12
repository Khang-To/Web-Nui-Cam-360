@extends('layouts.client')

@section('title', 'Khám phá Danh thắng - Mai Tùng House')

@section('content')
<section class="d-flex align-items-center" style="height: 40vh; min-height: 300px; background: linear-gradient(rgba(27, 94, 32, 0.8), rgba(0, 0, 0, 0.5)), url('/images/hero-background/danh-thang-banner.jpg') center/cover no-repeat; margin-top: 0;">
  <div class="container text-center pt-5">
    <span class="text-uppercase fw-bold text-warning mb-2 d-block" style="letter-spacing: 2px;" data-aos="fade-down">Điểm Đến</span>
    <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="100">Khám Phá Danh Thắng</h1>
    <p class="lead text-light mx-auto px-3" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="200">
      Những địa điểm check-in và tâm linh không thể bỏ lỡ khi đến với Thiên Cấm Sơn.
    </p>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container my-4">

    <div class="row g-4">
      @forelse($locations as $key => $item)
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($key % 3) * 100 }}">
          <a href="{{ route('client.location.detail', $item->id) }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm custom-card-hover">

              <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->name }}" style="height: 240px; object-fit: cover;">

              <div class="card-body p-4 d-flex flex-column">
                <h4 class="text-dark fw-bold mb-3">{{ $item->name }}</h4>

                <p class="text-muted mb-4 flex-grow-1">
                  {{ \Illuminate\Support\Str::limit($item->short_description, 120) }}
                </p>

                <div class="mt-auto">
                    <span class="text-success fw-bold small">
                      Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </span>
                </div>
              </div>

            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-mountain fa-4x text-muted opacity-25 mb-3"></i>
            <h4 class="text-muted">Đang cập nhật thêm danh lam thắng cảnh...</h4>
        </div>
      @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5 pt-3" data-aos="fade-up">
        {{ $locations->links() }}
    </div>

  </div>
</section>

<style>
  .custom-card-hover {
    transition: all 0.3s ease;
  }
  .custom-card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
  }
  .custom-card-hover h4 {
    transition: color 0.3s ease;
  }
  .custom-card-hover:hover h4 {
    color: var(--accent) !important;
  }

  /* CSS để thanh phân trang của Laravel ăn khớp với màu xanh của Mai Tùng House */
  .pagination .page-link { color: var(--primary); }
  .pagination .page-item.active .page-link { background-color: var(--primary); border-color: var(--primary); color: white; }
</style>
@endsection
