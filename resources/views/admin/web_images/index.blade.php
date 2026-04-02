@extends('layouts.admin')

@section('title', 'Quản lý Hình ảnh Web')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary">
            <i class="bi bi-image"></i> Quản lý hình ảnh (Banner, Carousel)
        </h3>

        <a href="{{ route('admin.web_images.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Thêm hình ảnh mới
        </a>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">#</th>
                            <th width="150">Hình ảnh</th>
                            <th>Phân loại vị trí</th>
                            <th>Tiêu đề</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center" width="150">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($webImages as $image)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Hình ảnh" class="img-thumbnail" style="height: 60px; width: 100px; object-fit: cover;">
                                </td>
                                <td>
                                    @if($image->group == 'carousel')
                                        <span class="badge bg-info">Carousel</span>
                                    @elseif ($image->group == 'banner_home')
                                        <span class="badge bg-primary">Banner - Trang chủ</span>
                                    @elseif($image->group == 'banner_about')
                                        <span class="badge bg-secondary">Banner - Giới thiệu</span>
                                    @else
                                        <span class="badge bg-dark">Banner - Danh thắng</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $image->title ?? '(Không có tiêu đề)' }}</strong>
                                </td>
                                <td class="text-center">
                                    @if($image->is_active)
                                        <span class="badge bg-success">Đang hiện</span>
                                    @else
                                        <span class="badge bg-danger">Đã ẩn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.web_images.toggle_active', $image->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $image->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $image->is_active ? 'Ẩn' : 'Hiện' }}">
                                            <i class="bi {{ $image->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.web_images.edit', $image->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.web_images.destroy', $image->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này? Dữ liệu và file gốc sẽ bị xóa vĩnh viễn!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Chưa có hình ảnh nào. Hãy thêm hình ảnh mới!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
