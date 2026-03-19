@extends('layouts.admin')

@section('title', 'Thêm Scene')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="text-primary mb-4">
        <i class="bi bi-plus-circle"></i> Thêm ảnh 360°
    </h3>

    <form action="{{ route('admin.scenes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tên scene --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">
                Tên ảnh <span class="text-danger">*</span>
            </label>

            <input type="text"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="Nhập tên ảnh">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ảnh panorama --}}
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Ảnh panorama <span class="text-danger">*</span>
            </label>

            <input type="file"
                   name="image"
                   class="form-control @error('image') is-invalid @enderror"
                   accept="image/*"
                   onchange="previewImage(event)">

            <small class="text-muted d-block">
                Chỉ chấp nhận ảnh equirectangular 360° (tỉ lệ rộng:dài khoảng 2:1).
            </small>

            @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            {{-- preview ảnh --}}
            <div class="mt-3">
                <img id="preview"
                     src="{{ asset('images/no-image.jpg') }}"
                     width="240"
                     class="img-thumbnail">
            </div>

        </div>


        {{-- Scene xuất phát --}}
        <div class="form-check mb-2">

            <input class="form-check-input"
                   type="checkbox"
                   name="is_default"
                   value="1"
                   id="is_default"
                   {{ old('is_default') ? 'checked' : '' }}>

            <label class="form-check-label fw-semibold" for="is_default">
                Đặt làm điểm xuất phát của tour
            </label>

            <div class="form-text">
                Nếu chọn, scene này sẽ là nơi người dùng bắt đầu khi mở tour 360°.
            </div>

        </div>


        {{-- Scene hiển thị menu --}}
        <div class="form-check mb-4">

            <input class="form-check-input"
                   type="checkbox"
                   name="is_start"
                   value="1"
                   id="is_start"
                   {{ old('is_start') ? 'checked' : '' }}>

            <label class="form-check-label fw-semibold" for="is_start">
                Hiển thị trong menu nhảy nhanh
            </label>

            <div class="form-text">
                Scene này sẽ xuất hiện trong menu để người dùng nhảy nhanh tới địa điểm.
            </div>

        </div>


        {{-- Nút lưu --}}
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu lại
        </button>

        <a href="{{ route('admin.scenes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>

    </form>

</div>
@endsection


@push('scripts')
<script>

/**
 * Hiển thị preview ảnh khi admin chọn file
 */
function previewImage(event)
{
    const reader = new FileReader();

    reader.onload = function()
    {
        document.getElementById('preview').src = reader.result;
    };

    reader.readAsDataURL(event.target.files[0]);
}

</script>
@endpush
