@extends('layouts.admin')

@section('title', 'Cấu hình tour 360°')

@section('content')
    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-primary">
                <i class="bi bi-image"></i> Quản lý ảnh 360°
            </h3>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-plus-circle"></i> Thêm ảnh 360°
            </button>
        </div>

        {{-- Tìm kiếm --}}
        <form method="GET" action="{{ route('admin.scenes.index') }}" class="row mb-3">
            <div class="col-md-4">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên ảnh...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Tìm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.scenes.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        {{-- Bảng danh sách Scene --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" width="60">STT</th>
                                <th class="text-center" width="150">Ảnh preview</th>
                                <th>Thông tin Scene</th>
                                <th width="240" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scenes as $index => $scene)
                                <tr>
                                    <td class="text-center">{{ $scenes->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        <img src="{{ $scene->image_url }}" style="height: 60px; object-fit: cover;" class="img-thumbnail w-100">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary fs-6 mb-1">{{ $scene->name }}</div>
                                        <div class="d-flex gap-2 flex-wrap mt-1">
                                            @if($scene->is_default)
                                                <span class="badge bg-success" title="Cảnh xuất hiện đầu tiên">
                                                    <i class="bi bi-play-circle-fill me-1"></i> Bắt đầu Tour
                                                </span>
                                            @endif
                                            @if($scene->is_start)
                                                <span class="badge bg-info text-dark" title="Cảnh hiển thị làm đại diện Menu">
                                                    <i class="bi bi-menu-button-wide-fill me-1"></i> Bắt đầu Menu
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.scenes.hotspots', $scene) }}" class="btn btn-sm btn-info text-white text-nowrap d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-gear"></i><span>Cấu hình</span>
                                            </a>
                                            <a href="{{ route('admin.scenes.edit', $scene) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Sửa
                                            </a>
                                            <form action="{{ route('admin.scenes.destroy', $scene) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa scene này? Các liên kết (hotspots) tới scene này cũng sẽ bị gỡ bỏ!')">
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
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-images fs-2 d-block mb-2 opacity-50"></i>
                                        Không có ảnh 360° nào. Hãy tải ảnh lên!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $scenes->links() }}
        </div>
    </div>

    <div class="modal fade" id="uploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true"
         data-upload-url="{{ route('admin.scenes.store') }}"
         data-csrf="{{ csrf_token() }}"
         data-index-url="{{ route('admin.scenes.index') }}">

        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="background-color: #f2f2f2;">

                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btnCloseModal"></button>
                </div>

                <div class="modal-body p-5 text-center" style="color: #333;">

                    <div id="step-2-upload" style="display: block;">
                        <h3 class="fw-light mb-4">Add some panoramas</h3>
                        <hr class="mb-4" style="border-color: #ccc;">

                        <div id="dropzone" class="p-5 mb-4" style="border: 2px dashed #aaa; border-radius: 4px; background: #fff; transition: all 0.3s; cursor: pointer;">
                            <i class="bi bi-images display-1 text-muted opacity-50 mb-2"></i>
                            <h5 class="text-muted fw-normal">Kéo thả file vào đây hoặc click để chọn ảnh</h5>

                            <div class="card text-start mt-4 mx-auto border-0 shadow-sm" style="max-width: 450px;">
                                <div class="card-header text-white fw-bold py-2" style="background-color: #2b98d3;">
                                    File requirements
                                </div>
                                <ul class="list-group list-group-flush small text-muted">
                                    <li class="list-group-item border-0 py-1"><i class="bi bi-dot"></i> Ảnh toàn cảnh 360° (Equirectangular)</li>
                                    <li class="list-group-item border-0 py-1"><i class="bi bi-dot"></i> Tỉ lệ khung hình (aspect ratio) chuẩn 2:1</li>
                                    <li class="list-group-item border-0 py-1"><i class="bi bi-dot"></i> Định dạng JPEG hoặc PNG</li>
                                    <li class="list-group-item border-0 py-1"><i class="bi bi-dot"></i> Tối đa 20MB mỗi file ảnh</li>
                                </ul>
                            </div>
                        </div>

                        <input type="file" id="fileInput" name="images[]" multiple accept="image/jpeg, image/png" style="display: none;">

                        <button class="btn btn-info text-white px-5 py-2 fs-5" style="background-color: #2b98d3; border-color: #2b98d3;" onclick="document.getElementById('fileInput').click()">
                            <i class="bi bi-cursor-fill me-2"></i>Select files
                        </button>
                    </div>

                    <div id="step-3-progress" style="display: none;">
                        <h3 class="fw-light mb-4">Đang xử lý tải lên...</h3>
                        <hr class="mb-4" style="border-color: #ccc;">

                        <div class="progress mb-3 shadow-sm" style="height: 30px; border-radius: 15px;">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 0%; font-size: 1rem; font-weight: bold;">0%</div>
                        </div>
                        <p id="progressText" class="text-muted fs-5">Đang chuẩn bị dữ liệu...</p>

                        <div class="spinner-border text-info mt-3" role="status" id="loadingSpinner" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin-assets/js/scene-upload.js') }}"></script>
@endpush
