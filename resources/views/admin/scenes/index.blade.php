@extends('layouts.admin')

@section('title', 'Cấu hình tour 360°')

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary">
            <i class="bi bi-image"></i> Quản lý ảnh 360°
        </h3>

        <a href="{{ route('admin.scenes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm ảnh 360°
        </a>
    </div>

    {{-- Tìm kiếm (tùy chọn) --}}
    <form method="GET" action="{{ route('admin.scenes.index') }}" class="row mb-3">
        <div class="col-md-4">
            <input type="text"
                   name="keyword"
                   value="{{ request('keyword') }}"
                   class="form-control"
                   placeholder="Tìm theo tên ảnh...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Tìm
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.scenes.index') }}" class="btn btn-secondary w-100">
                Reset
            </a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th width="60">STT</th>
                <th width="120">Ảnh preview</th>
                <th>Tên ảnh</th>
                <th width="220" class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($scenes as $index => $scene)
                <tr>
                    <td>{{ $scenes->firstItem() + $index }}</td>
                    <td>
                        <img src="{{ $scene->image_url }}" width="120" class="img-thumbnail">
                    </td>
                    <td class="fw-semibold text-primary">{{ $scene->name }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ url('admin/scenes/'.$scene->id.'/hotspots') }}" class="btn btn-sm btn-info">
                                <i class="bi bi-gear"></i> Hotspot
                            </a>
                            <a href="{{ route('admin.scenes.edit', $scene) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form action="{{ route('admin.scenes.destroy', $scene) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa scene này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
                        Không có ảnh 360° nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $scenes->links() }}
    </div>

</div>
@endsection
