@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary">
        <i class="bi bi-chat-left-text"></i> Quản lý đánh giá của khách hàng
    </h3>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-light rounded">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3 align-items-end">

            <div class="col-md-3">
                <label for="status" class="form-label fw-semibold text-secondary small mb-1">Trạng thái hiển thị</label>
                <select name="status" id="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa duyệt</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="rating" class="form-label fw-semibold text-secondary small mb-1">Số lượng sao</label>
                <select name="rating" id="rating" class="form-select">
                    <option value="">-- Tất cả số sao --</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Sao (Tuyệt vời)</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Sao (Tốt)</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Sao (Bình thường)</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Sao (Tệ)</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Sao (Rất tệ)</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="start_date" class="form-label fw-semibold text-secondary small mb-1">Từ ngày</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-2">
                <label for="end_date" class="form-label fw-semibold text-secondary small mb-1">Đến ngày</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary px-4 me-2">
                    <i class="bi bi-funnel"></i> Lọc dữ liệu
                </button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary px-3">
                    <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                </a>
            </div>

        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Khách hàng</th>
                        <th class="text-center">Số sao</th>
                        <th style="max-width: 300px;">Nội dung đánh giá</th>
                        <th class="text-center">Duyệt</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr id="row-review-{{ $review->id }}">
                        <td class="ps-4">
                            <div class="fw-bold">{{ $review->name }}</div>
                            <div class="small text-muted">{{ $review->email }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td class="text-center text-warning fs-6">
                            {{-- In ra sao bằng Bootstrap Icons --}}
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </td>
                        <td style="max-width: 300px;">
                            <div class="text-truncate" title="{{ $review->content }}">
                                {{ $review->content }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input toggle-approve" type="checkbox" role="switch"
                                    data-url="{{ route('admin.reviews.update', $review) }}"
                                    {{ $review->is_approved ? 'checked' : '' }} style="cursor: pointer;">
                            </div>
                        </td>
                        <td class="text-end pe-4 text-nowrap">
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-outline-info me-1">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                            <button class="btn btn-sm btn-outline-danger btn-delete"
                                data-url="{{ route('admin.reviews.destroy', $review) }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary opacity-50"></i>
                            Chưa tìm thấy đánh giá nào phù hợp với bộ lọc.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($reviews->hasPages())
    <div class="card-footer bg-white pt-3 pb-1 border-top-0">
        {{ $reviews->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');

    // Đồng bộ ngày bắt đầu và kết thúc để tránh chọn ngày không hợp lệ
    if(startInput && endInput) {
        startInput.addEventListener('change', () => endInput.min = startInput.value || '');
        endInput.addEventListener('change', () => startInput.max = endInput.value || '');
    }

    // 1. AJAX: Bật/Tắt duyệt đánh giá (Method PUT)
    document.querySelectorAll('.toggle-approve').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const url = this.getAttribute('data-url');
            const checkbox = this;

            checkbox.disabled = true;

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw data;
                return data;
            })
            .then(data => {
                showToast(data.message, "success");
            })
            .catch(err => {
                showToast(err.message || "Lỗi cập nhật trạng thái!", "error");
                checkbox.checked = !checkbox.checked;
            })
            .finally(() => {
                checkbox.disabled = false;
            });
        });
    });

    // 2. AJAX: Xóa đánh giá (Method DELETE)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Bạn có chắc chắn muốn xóa đánh giá này vĩnh viễn không?')) return;

            const url = this.getAttribute('data-url');
            const row = this.closest('tr');
            const originalHtml = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>...';

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw data;
                return data;
            })
            .then(data => {
                showToast(data.message, "success");
                row.style.transition = "opacity 0.4s ease, transform 0.4s ease";
                row.style.opacity = 0;
                row.style.transform = "translateX(20px)";
                setTimeout(() => row.remove(), 400);
            })
            .catch(err => {
                showToast(err.message || "Không thể xóa đánh giá này!", "error");
                this.disabled = false;
                this.innerHTML = originalHtml;
            });
        });
    });
});
</script>
@endpush
