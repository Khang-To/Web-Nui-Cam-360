@extends('layouts.admin')

@section('title', 'Chỉnh sửa Scene')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="text-primary mb-4">
        <i class="bi bi-pencil"></i> Chỉnh sửa ảnh 360°
    </h3>

    <form action="{{ route('admin.scenes.update', $scene) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Tên scene --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Tên ảnh <span class="text-danger">*</span></label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $scene->name) }}"
                   placeholder="Nhập tên ảnh">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ảnh hiện tại --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh hiện tại</label>
            <div class="mt-2">
                <img src="{{ $scene->image_url }}" width="240" class="img-thumbnail">
            </div>
            <small class="text-muted">Không thể thay đổi ảnh sau khi tạo</small>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu lại
        </button>

        <a href="{{ route('admin.scenes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </form>

</div>
@endsection
