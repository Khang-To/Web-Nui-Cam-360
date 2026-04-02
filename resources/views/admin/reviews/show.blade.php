@extends('layouts.admin')

@section('title', 'Chi tiết đánh giá')

@section('content')
    <div class="container py-4">

        <!-- HEADER -->
        <h4 class="fw-bold mb-4">
            <i class="bi bi-chat-left-quote text-primary me-2"></i>
            Chi tiết đánh giá
        </h4>

        <!-- THÔNG TIN KHÁCH -->
        <h5 class="fw-bold mb-3">Thông tin khách hàng</h5>

        <p><strong>Họ tên:</strong> {{ $review->name }}</p>
        <p><strong>Email:</strong> {{ $review->email }}</p>

        <!-- ĐÁNH GIÁ -->
        <h5 class="fw-bold mt-4 mb-3">Đánh giá</h5>

        <!-- ⭐ STAR FIX -->
        <div class="text-warning mb-2 fs-5">
            @php $rating = $review->rating ?? 0; @endphp
            @for ($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }}"></i>
            @endfor
        </div>

        <p class="fst-italic" style="white-space: pre-line;">
            “{{ $review->content }}”
        </p>

        <!-- THỜI GIAN -->
        <p class="text-muted">
            <i class="bi bi-clock"></i>
            {{ $review->created_at->format('d/m/Y H:i') }}
        </p>

        <!-- TRẠNG THÁI -->
        <h5 class="fw-bold mt-4 mb-2">Trạng thái</h5>

        @if ($review->is_approved)
            <span class="badge bg-success">Đang hiển thị</span>
        @else
            <span class="badge bg-secondary">Đang ẩn</span>
        @endif

        <!-- ACTION -->
        <div class="mt-4 d-flex gap-2">

            <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">

                <button type="submit" class="btn {{ $review->is_approved ? 'btn-warning' : 'btn-success' }}">
                    <i class="bi {{ $review->is_approved ? 'bi-eye-slash' : 'bi-check-circle' }}"></i>
                    {{ $review->is_approved ? 'Ẩn đánh giá' : 'Duyệt đánh giá' }}
                </button>
            </form>

            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                onsubmit="return confirm('Xóa vĩnh viễn đánh giá này?')">
                @csrf @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </form>

            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                Quay lại
            </a>

        </div>

    </div>
@endsection
