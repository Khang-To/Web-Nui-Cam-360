@extends('layouts.admin')

@section('title', 'Thêm hình ảnh mới')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Thêm hình ảnh mới</h1>
        <a href="{{ route('admin.web_images.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <form action="{{ route('admin.web_images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phân loại vị trí hiển thị <span class="text-danger">*</span></label>
                            <select name="group" id="imageGroup" class="form-select @error('group') is-invalid @enderror" required>
                                <option value="carousel" {{ old('group') == 'carousel' ? 'selected' : '' }}>Carousel (Slider chạy ở Trang chủ)</option>
                                <option value="banner_home" {{ old('group') == 'banner_home' ? 'selected' : '' }}>Banner - Trang chủ</option>
                                <option value="banner_about" {{ old('group') == 'banner_about' ? 'selected' : '' }}>Banner - Trang Giới thiệu</option>
                                <option value="banner_location" {{ old('group') == 'banner_location' ? 'selected' : '' }}>Banner - Trang Danh thắng</option>
                            </select>
                            @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="titleWrapper">
                            <label class="form-label fw-bold">Tiêu đề</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Nhập tiêu đề...">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="descWrapper">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Nhập mô tả ngắn...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tải lên hình ảnh <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                            <div class="form-text">Định dạng: JPG, PNG, WEBP. Tối đa 5MB.</div>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="orderWrapper">
                            <label class="form-label fw-bold">Thứ tự hiển thị</label>
                            <input type="text" class="form-control bg-light text-muted" value="{{ $nextOrder }}" readonly>
                            <div class="form-text">Hệ thống sẽ tự động xếp ảnh này vào cuối danh sách.</div>
                        </div>

                        <div class="mb-3 form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" checked>
                            <label class="form-check-label fw-bold" for="isActive">Bật hiển thị ảnh này</label>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Lưu hình ảnh</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const groupSelect = document.getElementById('imageGroup');
        const descWrapper = document.getElementById('descWrapper');
        const orderWrapper = document.getElementById('orderWrapper');
        const titleWrapper = document.getElementById('titleWrapper');

        function toggleFields() {
            // Nếu value bắt đầu bằng chữ 'banner' thì ẩn mô tả và thứ tự
            if (groupSelect.value.startsWith('banner')) {
                descWrapper.style.display = 'none';
                orderWrapper.style.display = 'none';
                titleWrapper.style.display = 'none';
            } else {
                descWrapper.style.display = 'block';
                orderWrapper.style.display = 'block';
                titleWrapper.style.display = 'block';
            }
        }

        toggleFields();
        groupSelect.addEventListener('change', toggleFields);
    });
</script>
@endsection
