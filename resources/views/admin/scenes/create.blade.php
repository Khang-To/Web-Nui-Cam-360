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
            <label class="form-label fw-semibold">Tên ảnh <span class="text-danger">*</span></label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="Nhập tên ảnh">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ảnh panorama --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh panorama <span class="text-danger">*</span></label>
            <input type="file" name="image"
                   class="form-control @error('image') is-invalid @enderror"
                   accept="image/*" onchange="previewImage(event)">
            <small class="text-muted d-block">Chỉ chấp nhận ảnh equirectangular 360° (tỉ lệ rộng:dài khoảng 2:1).</small>
            @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <div class="mt-3">
                <img id="preview" src="{{ asset('images/no-image.jpg') }}" width="240" class="img-thumbnail">
            </div>
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

@push('scripts')
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush
