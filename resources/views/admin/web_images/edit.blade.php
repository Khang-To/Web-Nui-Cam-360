@extends('layouts.admin')

@section('title', 'Cập nhật hình ảnh')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800">Cập nhật hình ảnh</h1>
        <a href="{{ route('admin.web_images.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <form action="{{ route('admin.web_images.update', $webImage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phân loại vị trí hiển thị <span class="text-danger">*</span></label>
                            <select name="group" id="imageGroup" class="form-select @error('group') is-invalid @enderror" required>
                                <option value="carousel" {{ old('group', $webImage->group) == 'carousel' ? 'selected' : '' }}>Carousel</option>
                                <option value="banner_home" {{ old('group', $webImage->group) == 'banner_home' ? 'selected' : '' }}>Banner - Trang chủ</option>
                                <option value="banner_about" {{ old('group', $webImage->group) == 'banner_about' ? 'selected' : '' }}>Banner - Trang Giới thiệu</option>
                                <option value="banner_location" {{ old('group', $webImage->group) == 'banner_location' ? 'selected' : '' }}>Banner - Trang Danh thắng</option>
                            </select>
                            @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="titleWrapper">
                            <label class="form-label fw-bold">Tiêu đề</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $webImage->title) }}">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="descWrapper">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $webImage->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Hình ảnh hiện tại</label>
                            <img src="{{ asset('storage/' . $webImage->image_path) }}" alt="Current Image" class="img-fluid rounded mb-2 border" style="max-height: 150px;">

                            <label class="form-label fw-bold mt-2">Thay đổi hình ảnh (Để trống nếu giữ nguyên)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3" id="orderWrapper">
                            <label class="form-label fw-bold">Thứ tự hiển thị</label>
                            <input type="text" class="form-control bg-light text-muted" value="{{ $webImage->order }}" readonly>
                            <div class="form-text">Thứ tự do hệ thống tự động sắp xếp.</div>
                        </div>

                        <div class="mb-3 form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $webImage->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">Bật hiển thị ảnh này</label>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Lưu thay đổi</button>
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
