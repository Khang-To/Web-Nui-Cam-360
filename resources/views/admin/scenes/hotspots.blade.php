@extends('layouts.admin')

@section('title', 'Cấu hình Scene 360')

@section('content')

    <div class="container-fluid">

        <h3 class="mb-3">
            Cấu hình Scene: {{ $scene->name }}
        </h3>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="d-flex gap-3">
                <a href="{{ route('admin.scenes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Danh sách
                </a>

                <a href="{{ route('admin.scenes.edit', $scene) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Sửa
                </a>

                <select onchange="location = this.value" class="form-select w-auto">
                    @foreach ($scenes as $s)
                        <option value="{{ route('admin.scenes.hotspots', $s->id) }}"
                            {{ $s->id == $scene->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button id="btnSetView" class="btn btn-primary">
                <i class="bi bi-eye"></i> Đặt chế độ xem ban đầu
            </button>

        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body p-2">
                <div id="pano"></div>
            </div>
        </div>

        <p class="text-muted mb-3">
            <i class="bi bi-info-circle"></i>
            <strong>Nhấn đúp chuột (double-click)</strong> vào bất kỳ vị trí nào trên ảnh 360° để thêm hotspot mới.
        </p>

    </div>

    <div class="modal fade" id="hotspotModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Cấu hình Hotspot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="hotspot_id">
                    <input type="hidden" id="yaw">
                    <input type="hidden" id="pitch">

                    <input type="hidden" id="target_yaw">
                    <input type="hidden" id="target_pitch">
                    <input type="hidden" id="target_fov">

                    <div class="mb-4">
                        <label class="fw-bold mb-1">Loại Hotspot</label>
                        <select id="type" class="form-select">
                            <option value="link">Điểm di chuyển</option>
                            <option value="info">Điểm thông tin</option>
                        </select>
                    </div>

                    <div id="link-group">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="fw-bold mb-1">Scene đích (Chuyển đến đâu?)</label>
                                    <select id="target_scene_id" class="form-select">
                                        <option value="">-- Chọn Scene đích --</option>
                                        @foreach ($scenes as $s)
                                            <option value="{{ $s->id }}" data-image="{{ $s->image_url }}">
                                                {{ $s->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-2">
                                    <label class="fw-bold mb-1">Góc nhìn tại Scene đích</label>
                                    <div id="target-pano" class="shadow-sm"
                                        style="width:100%; height:250px; background:#000; border-radius:6px; position:relative;">
                                    </div>
                                    <button id="btnSetTargetView" class="btn btn-sm btn-outline-primary mt-2 w-100">
                                        <i class="bi bi-crosshair"></i> Chọn góc nhìn tại Scene đích
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4 border-start d-flex flex-column align-items-center justify-content-center">
                                <label class="fw-bold mb-3 text-center">Xoay Icon (Hướng đi)</label>

                                <div class="p-3 mb-4 rounded-circle bg-light shadow-sm d-flex align-items-center justify-content-center"
                                    style="width: 80px; height: 80px;">
                                    <img id="icon-preview" src="/images/icons/link.png" class="icon-preview"
                                        style="width: 40px;">
                                </div>

                                <div class="w-100 px-2">
                                    <input type="range" id="rotation" class="form-range" min="0" max="360"
                                        value="0">
                                    <div class="text-center small text-muted mt-1">Trái <i
                                            class="bi bi-arrow-left-right"></i> Phải</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="info-group" style="display:none">
                        <div class="mb-3">
                            <label class="fw-bold mb-1">Lọc theo Địa điểm</label>
                            <select id="filter_location_id" class="form-select">
                                <option value="all">-- Hiện tất cả đối tượng --</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-1">Đối tượng tham quan</label>
                            <select id="tourist_object_id" class="form-select">
                                <option value="">-- Chọn đối tượng thông tin --</option>
                                @foreach ($touristObjects as $obj)
                                    <option value="{{ $obj->id }}" data-location="{{ $obj->location_id }}">
                                        {{ $obj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button id="deleteHotspot" type="button" class="btn btn-danger me-auto" style="display: none;">
                        <i class="bi bi-trash"></i> Xóa
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button id="saveHotspot" type="button" class="btn btn-success">
                        <i class="bi bi-save"></i> Lưu Hotspot
                    </button>
                </div>

            </div>
        </div>
    </div>

    <style>
        #pano {
            width: 100%;
            height: 60vh;
            min-height: 500px;
            background: black;
            overflow: hidden;
            border-radius: 8px;
            position: relative;
        }

        /* fix tràn Marzipano canvas */
        #pano canvas,
        #pano>div,
        #target-pano canvas,
        #target-pano>div {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* ================= HOTSPOT FIX GIẬT VÀ XOAY ================= */
        .hotspot-wrapper {
            position: absolute;
            cursor: pointer;
            /* Khung wrapper lo việc căn giữa và xoay bằng JS, không bị dính hiệu ứng hover */
        }

        .hotspot-icon {
            width: 40px;
            height: 40px;
            display: block;
            filter: drop-shadow(0px 2px 4px rgba(0, 0, 0, 0.6));
            /* Chỉ transition hiệu ứng phóng to cho thẻ img con */
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), filter 0.2s;
        }

        /* Hover vào khung, chỉ phóng to cái ảnh, KHÔNG làm mất tọa độ xoay của khung */
        .hotspot-wrapper:hover .hotspot-icon {
            transform: scale(1.25);
            filter: drop-shadow(0px 6px 12px rgba(255, 255, 255, 0.4));
        }

        /* Style cho nhãn tên Hotspot */
        .hotspot-label {
            position: absolute;
            bottom: 100%;
            /* Hiện phía trên icon */
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            /* Đẩy xuống một chút để diễn hoạt */
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            white-space: nowrap;
            /* Không cho xuống dòng */
            opacity: 0;
            pointer-events: none;
            /* Không cản trở click vào icon */
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 8px;
        }

        /* Tam giác nhỏ phía dưới nhãn */
        .hotspot-label::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
        }

        /* Hiệu ứng khi Hover vào Wrapper */
        .hotspot-wrapper:hover .hotspot-label {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    </style>

@endsection

@push('scripts')
    <script src="{{ asset('js/marzipano.js') }}"></script>

    <script>
        // TRUYỀN DỮ LIỆU TỪ LARAVEL SANG JAVASCRIPT
        window.sceneData = {
            id: {{ $scene->id }},
            image: "{{ $scene->image_url }}",
            yaw: {{ $scene->initial_yaw ?? 0 }},
            pitch: {{ $scene->initial_pitch ?? 0 }},
            fov: {{ $scene->initial_fov ?? 'null' }}
        };

        window.hotspots = @json($scene->hotspots);
        window.allScenes = @json($scenes);
    </script>

    <script src="{{ asset('admin-assets/js/scene-editor.js') }}"></script>
@endpush
