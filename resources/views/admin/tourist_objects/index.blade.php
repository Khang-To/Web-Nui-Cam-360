@extends('layouts.admin')

@section('title', 'Quản lý đối tượng tham quan')

@section('content')
    <div class="container-fluid mt-4">

        {{-- Tiêu đề + nút thêm --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-primary">
                <i class="bi bi-star-fill"></i> Quản lý đối tượng tham quan
            </h3>

            <a href="{{ route('admin.tourist_objects.create') }}" class="btn btn-primary">
                Thêm đối tượng tham quan
            </a>
        </div>

        {{-- Tìm kiếm --}}
        <form method="GET" action="{{ route('admin.tourist_objects.index') }}" class="row mb-3">

            <div class="col-md-3">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                    placeholder="Tìm theo tên...">
            </div>

            <div class="col-md-3">
                <select name="location_id" class="form-control">
                    <option value="">-- Chọn địa điểm --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" @selected(request('location_id') == $location->id)>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>

            <div class="col-md-2">
                <a href="{{ route('admin.tourist_objects.index') }}" class="btn btn-secondary w-100">
                    Reset
                </a>
            </div>

        </form>

        {{-- Bảng --}}
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th width="120">Hình ảnh</th>
                    <th>Tên đối tượng</th>
                    <th>Địa điểm</th>
                    <th>Mô tả</th>
                    <th width="200" class="text-center">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @forelse($touristObjects as $obj)
                    <tr>
                        <td>
                            <img src="{{ $obj->image_url ?? asset('images/no-image.jpg') }}" width="100"
                                class="img-thumbnail">
                        </td>

                        <td class="fw-semibold text-primary">
                            {{ $obj->name }}
                        </td>

                        <td>
                            <span class="badge bg-info">
                                {{ $obj->location->name }}
                            </span>
                        </td>

                        <td style="max-width:320px">
                            {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($obj->description)), 120) }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.tourist_objects.edit', $obj) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>

                            <form action="{{ route('admin.tourist_objects.destroy', $obj) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            Không tìm thấy đối tượng tham quan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="mt-3">
            {{ $touristObjects->links() }}
        </div>

    </div>
@endsection
