@extends('layouts.admin')

@section('title', 'Bảng điều khiển - Núi Cấm 360')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-house-door-fill text-primary"></i>
                <strong>Bảng điều khiển</strong>
            </h1>
            <p class="text-muted mb-0">Tổng quan hệ thống quản lý tour 360° Núi Cấm</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Hôm nay: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
        </div>
    </div>

    {{-- Thống kê --}}
    <div class="row mb-4">
        {{-- Địa điểm --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Địa điểm du lịch
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Location::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-geo-alt-fill fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Đối tượng du lịch --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đối tượng tham quan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\TouristObject::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-star-fill fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scenes (Đã thay thế vị trí của Hotspot) --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Cảnh quan 360°
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Scene::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-camera-fill fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Đánh giá chờ duyệt --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Đánh giá chờ duyệt
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pendingReviewCount ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-chat-left-dots-fill fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h5 class="card-title m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        Hành động nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.locations.create') }}"
                               class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-plus-circle-fill fa-2x mb-2"></i>
                                <span>Thêm địa điểm</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.tourist_objects.create') }}"
                               class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-plus-circle-fill fa-2x mb-2"></i>
                                <span>Thêm đối tượng</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.locations.index') }}"
                               class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-list-ul fa-2x mb-2"></i>
                                <span>Danh sách địa điểm</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.tourist_objects.index') }}"
                               class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-list-ul fa-2x mb-2"></i>
                                <span>Danh sách đối tượng</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KHU VỰC ĐÁNH GIÁ CẦN DUYỆT --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-bottom-danger">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-danger">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>Đánh giá mới cần duyệt
                    </h5>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-danger">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @if(isset($pendingReviews) && $pendingReviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Mức độ</th>
                                        <th>Nội dung</th>
                                        <th>Thời gian gửi</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingReviews as $review)
                                        <tr>
                                            <td>
                                                <strong>{{ $review->name }}</strong><br>
                                                <small class="text-muted">{{ $review->email }}</small>
                                            </td>
                                            <td class="text-warning text-nowrap">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </td>
                                            <td>{{ \Illuminate\Support\Str::limit($review->content, 80) }}</td>
                                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center text-nowrap">
                                                <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="is_approved" value="1">
                                                    <button type="submit" class="btn btn-success btn-sm" title="Duyệt cho phép hiển thị">
                                                        <i class="bi bi-check-lg"></i> Duyệt
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn đánh giá này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa bỏ">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-dark">Tuyệt vời!</h5>
                            <p class="mb-0">Hiện không có đánh giá nào đang chờ duyệt.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- KHU VỰC THỐNG KÊ ĐÁNH GIÁ --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-info">

                {{-- Card Header: Tiêu đề & Form Lọc Ngày --}}
                <div class="card-header py-3 bg-white border-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-info mb-3 mb-md-0">
                        <i class="bi bi-bar-chart-line-fill me-2"></i>Thống kê Đánh giá Tổng quan
                    </h5>

                    {{-- Form Lọc Ngày (Đã thêm ID filter-stats-form) --}}
                    <form id="filter-stats-form" action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">Từ</span>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                            <span class="input-group-text bg-light border-start-0 border-end-0">Đến</span>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                            <button type="submit" class="btn btn-info text-white px-3" id="btn-submit-filter">
                                <i class="bi bi-filter"></i> Lọc
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter" title="Xóa lọc">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Card Body: Bảng Progress Bar --}}
                <div class="card-body bg-light">
                    <div class="row text-center align-items-center">

                        <div class="col-md-4 border-end mb-4 mb-md-0">
                            <div class="text-muted mb-2 text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Tổng số đánh giá</div>
                            <h2 id="total-reviews-text" class="fw-bold text-dark display-4 mb-0" style="transition: all 0.3s;">
                                {{ $totalReviews }}
                            </h2>

                            <hr class="w-50 mx-auto my-3 text-muted">

                            <div class="text-muted mb-2 text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Điểm trung bình</div>
                            <h2 id="avg-rating-text" class="fw-bold text-warning mb-0" style="transition: all 0.3s;">
                                {{ $averageRating }} <i class="bi bi-star-fill fs-3 align-baseline"></i>
                            </h2>
                        </div>

                        <div class="col-md-8 text-start ps-md-5">
                            @for ($i = 5; $i >= 1; $i--)
                                @php
                                    $percentage = $totalReviews > 0 ? ($starCounts[$i] / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width: 65px;" class="text-dark fw-bold fs-6">
                                        {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                                    </div>

                                    <div class="progress flex-grow-1 mx-3" style="height: 14px; border-radius: 10px; background-color: #e9ecef;">
                                        <div id="star-progress-{{ $i }}" class="progress-bar {{ $i >= 4 ? 'bg-success' : ($i == 3 ? 'bg-warning' : 'bg-danger') }}"
                                             role="progressbar"
                                             style="width: {{ $percentage }}%; border-radius: 10px; transition: width 0.6s ease;"
                                             aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>

                                    <div id="star-count-{{ $i }}" style="width: 50px;" class="text-end text-muted fw-semibold">
                                        {{ $starCounts[$i] }}
                                    </div>
                                </div>
                            @endfor
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-dashboard.css') }}">
<style>
    .border-left-danger { border-left: .25rem solid #dc3545 !important; }
    .border-bottom-danger { border-bottom: .25rem solid #dc3545 !important; }
    /* CSS hỗ trợ thêm cho thẻ màu Info (Xanh dương nhạt) */
    .border-left-info { border-left: .25rem solid #0dcaf0 !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-stats-form');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const btnReset = document.getElementById('btn-reset-filter');
    const btnSubmit = document.getElementById('btn-submit-filter');

    // 1. Ràng buộc ngày bắt đầu/kết thúc không được lỗi logic
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value || '';
        });
        endDateInput.addEventListener('change', function() {
            startDateInput.max = this.value || '';
        });
    }

    // 2. Xử lý Gửi Form bằng AJAX
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Chặn load trang

            // Đổi trạng thái nút
            const originalBtnHtml = btnSubmit.innerHTML;
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            // Lấy URL và nối tham số form vào
            const url = new URL(this.action);
            const formData = new FormData(this);
            formData.forEach((value, key) => {
                if(value) url.searchParams.append(key, value);
            });

            // Gửi Fetch API
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Báo cho Laravel biết đây là AJAX
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw data;
                return data;
            })
            .then(data => {
                // Cập nhật Tổng số và Điểm trung bình
                document.getElementById('total-reviews-text').innerText = data.totalReviews;
                document.getElementById('avg-rating-text').innerHTML = `${data.averageRating} <i class="bi bi-star-fill fs-3 align-baseline"></i>`;

                // Cập nhật Thanh Progress Bar và Con số chi tiết
                for (let i = 5; i >= 1; i--) {
                    document.getElementById('star-count-' + i).innerText = data.starCounts[i];
                    document.getElementById('star-progress-' + i).style.width = data.percentages[i] + '%';
                    document.getElementById('star-progress-' + i).setAttribute('aria-valuenow', data.percentages[i]);
                }

                showToast('Đã cập nhật thống kê!', 'success');
            })
            .catch(err => {
                let errorMsg = "Lỗi khi lọc dữ liệu!";
                if (err.errors) {
                    errorMsg = Object.values(err.errors)[0][0]; // Lấy lỗi Validate từ Laravel
                }
                showToast(errorMsg, 'error');
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalBtnHtml;
            });
        });
    }

    // 3. Xử lý Nút Xóa Lọc (Reset)
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            filterForm.reset();
            startDateInput.max = '';
            endDateInput.min = '';
            // Tự động kích hoạt sự kiện submit để load lại dữ liệu mặc định (tất cả)
            filterForm.dispatchEvent(new Event('submit'));
        });
    }
});
</script>
@endpush
