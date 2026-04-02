@extends('layouts.admin')

@section('title', 'Cấu hình Website')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold text-primary"><i class="bi bi-gear-fill me-2"></i>Cấu hình hệ thống</h4>
</div>

<form action="{{ route('admin.web_settings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        {{-- CỘT TRÁI: THÔNG TIN CƠ BẢN --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-info"></i>Thông tin liên hệ</h6>
                </div>
                <div class="card-body">

                    {{-- Hotline, Email, Địa chỉ, Link Facebook, Link Video, Mã nhúng Google Map --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hotline</label>
                            <input type="text" name="settings[phone]" class="form-control" value="{{ $settings['phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="settings[email]" class="form-control" value="{{ $settings['email'] ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ</label>
                        <input type="text" name="settings[address]" class="form-control" value="{{ $settings['address'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Fanpage Facebook</label>
                        <input type="url" name="settings[facebook]" class="form-control" placeholder="https://facebook.com/..." value="{{ $settings['facebook'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Video Giới thiệu (YouTube/TikTok...)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-danger"><i class="bi bi-play-btn-fill"></i></span>
                            <input type="url" name="settings[video_link]" class="form-control" placeholder="https://youtube.com/watch?v=..." value="{{ $settings['video_link'] ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Mã nhúng Google Map (Iframe)</label>
                        <textarea name="settings[map_iframe]" class="form-control text-muted" rows="4" placeholder='<iframe src="..."></iframe>'>{{ $settings['map_iframe'] ?? '' }}</textarea>
                        <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-triangle"></i> Chỉ dán thẻ iframe lấy từ Google Maps.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: HÌNH ẢNH (LOGO) --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-image me-2 text-success"></i>Hình ảnh thương hiệu</h6>
                </div>
                <div class="card-body text-center">
                    <label class="form-label fw-semibold d-block text-start">Logo Web</label>

                    {{-- Khung Preview Logo --}}
                    <div class="p-3 bg-light border rounded mb-3 d-flex justify-content-center align-items-center" style="height: 150px;">
                        @if(isset($settings['logo']) && $settings['logo'])
                            <img id="logo-preview" src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="img-fluid" style="max-height: 120px;">
                        @else
                            <span id="logo-preview-text" class="text-muted">Chưa có Logo</span>
                            <img id="logo-preview" src="" alt="Logo" class="img-fluid d-none" style="max-height: 120px;">
                        @endif
                    </div>

                    {{-- Logo KHÔNG nằm trong mảng settings, đứng riêng để upload --}}
                    <input type="file" name="logo" id="logo-input" class="form-control form-control-sm" accept="image/*">
                    <small class="text-muted d-block mt-2">Định dạng: JPG, PNG. Tối đa 2MB.</small>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-primary-subtle">
                <div class="card-body text-center p-4">
                    <h6 class="mb-3 text-primary">Bạn đã thay đổi xong?</h6>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-save me-2"></i> Lưu Cấu Hình
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // JS: Tự động tải hình Preview ngay lập tức khi Admin chọn File Logo
    document.getElementById('logo-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('logo-preview');
                const text = document.getElementById('logo-preview-text');
                img.src = e.target.result;
                img.classList.remove('d-none');
                if(text) text.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
