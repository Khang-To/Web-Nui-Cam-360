@extends('layouts.admin')

@section('title', 'Quản lý địa điểm')

@section('content')
<div class="container-fluid mt-4">

    {{-- Tiêu đề + nút thêm --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-primary">
            <i class="bi bi-geo-alt-fill"></i> Quản lý địa điểm du lịch
        </h3>

        <a href="{{ route('admin.locations.create') }}"
           class="btn btn-primary">
            Thêm địa điểm
        </a>
    </div>

    {{-- Tìm kiếm --}}
    <form method="GET"
          action="{{ route('admin.locations.index') }}"
          class="row mb-3">

        <div class="col-md-4">
            <input type="text"
                   name="keyword"
                   value="{{ request('keyword') }}"
                   class="form-control"
                   placeholder="Tìm theo tên địa điểm...">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                <i class="bi bi-search"></i> Tìm
            </button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('admin.locations.index') }}"
               class="btn btn-secondary w-100">
                Reset
            </a>
        </div>

    </form>

    {{-- Bảng --}}
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th width="120">Hình ảnh</th>
                <th>Tên địa điểm</th>
                <th>Mô tả ngắn</th>
                <th width="180" class="text-center">Hành động</th>
            </tr>
        </thead>

        <tbody>
            @forelse($locations as $location)
                <tr>
                    <td>
                        <img src="{{ $location->image_url }}"
                             width="100"
                             class="img-thumbnail">
                    </td>

                    <td class="fw-semibold text-primary">
                        {{ $location->name }}
                    </td>

                    <td>
                        {{ \Illuminate\Support\Str::limit($location->short_description, 80) }}
                    </td>

                    <td class="text-center">
                        <a href="{{ route('admin.locations.edit', $location) }}"
                           class="btn btn-sm btn-warning">
                            Sửa
                        </a>

                        <form action="{{ route('admin.locations.destroy', $location) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-danger">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5"
                        class="text-center text-muted py-3">
                        Không tìm thấy địa điểm
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="mt-3">
        {{ $locations->links() }}
    </div>

</div>
@endsection
