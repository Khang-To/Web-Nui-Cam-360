@extends('layouts.virtual-tour')

@section('content')

<div id="tour-app">

    <div id="topBar">
        <div class="top-left">
            <div id="toggleMenuBtn" title="Danh sách cảnh">
                <img src="{{ asset('images/icons/collapse.png') }}" alt="Menu">
            </div>
        </div>

        <div id="sceneNameBar">
            <span id="sceneName">Đang tải cảnh quan...</span>
        </div>

        <div class="top-right-controls">
            <div id="btnFullscreen" class="btn-top-control" title="Toàn màn hình">
                <img src="{{ asset('images/icons/fullscreen.png') }}" alt="Fullscreen">
            </div>
            <a href="{{ route('home') }}" class="btn-top-control close-btn" title="Trở về Trang chủ">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </div>

    @if(!$defaultScene)
        <div class="alert alert-warning text-center mt-5" style="position: absolute; top: 50px; width: 100%; z-index: 9999;">
            ⚠️ Chưa có dữ liệu tour. Vui lòng thêm cảnh trong trang quản trị.
        </div>
    @endif

    <div id="menu" class="menu closed">
        <ul>
            @if(isset($menuScenes) && count($menuScenes) > 0)
                @foreach($menuScenes as $scene)
                    <li class="menu-item" data-id="{{ $scene->id }}" title="{{ $scene->name }}">
                        {{ $scene->name }}
                    </li>
                @endforeach
            @else
                <li style="padding: 15px; color: #ccc;">Chưa có dữ liệu</li>
            @endif
        </ul>
    </div>

    <div id="pano"></div>

</div>

<div class="modal fade" id="infoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success fw-bold" id="infoTitle" style="font-family: 'Playfair Display', serif;"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body d-flex flex-column overflow-hidden">

                <img id="infoImage" class="img-fluid mb-3 rounded shadow-sm w-100 flex-shrink-0"
                     style="max-height: 350px; object-fit: cover; display: none;"
                     alt="Hình ảnh đối tượng"
                     onerror="console.error('LỖI ĐƯỜNG DẪN ẢNH: ' + this.src); this.style.border='2px dashed red';">

                <div id="infoContent" class="text-start overflow-y-auto pe-2" style="line-height: 1.6;"></div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="welcomeModal" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content overflow-hidden border-0 shadow-lg">
            <div class="row g-0">
                <div class="col-md-5 d-none d-md-block" style="background: url('{{ asset('images/hero-background/banner-khu-du-lich-nui-cam.jpg') }}') center/cover; min-height: 100%;"></div>
                <div class="col-md-7">
                    <div class="modal-body p-4 p-md-5 text-center text-md-start">
                        <h3 class="mb-3" style="color: #1B5E20; font-weight: 700;">
                            Chào mừng bạn đến với trang Tour 360° của Mai Tùng!
                        </h3>
                        <p class="text-muted mb-4" style="line-height: 1.7; font-size: 15px;">
                            Hành trình tham quan ảo này sẽ chỉ dẫn bạn di chuyển từ nhà nghỉ đến các địa điểm nổi bật trên núi Cấm.
                        </p>
                        <p class="mb-4 fw-medium text-dark">
                            <i class="fas fa-hand-pointer text-warning me-2"></i> Vuốt màn hình và chạm vào các biểu tượng mũi tên để bắt đầu di chuyển!
                        </p>
                        <div class="mt-4 text-center text-sm-start">
                            <button id="startTour" class="btn btn-success px-4 py-2" style="background: #F57C00; border: none; border-radius: 30px; font-weight: 600;" data-bs-dismiss="modal">
                                Bắt đầu khám phá <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('client-assets/css/virtual-tour.css') }}">
@endpush

@push('scripts')
    <script>
        window.defaultSceneId = {{ $defaultScene->id ?? 'null' }};
        window.sceneUrl = "{{ route('client.virtualtour.scene', ':id') }}";
    </script>
    <script src="{{ asset('js/marzipano.js') }}"></script>
    <script src="{{ asset('client-assets/js/virtual-tour.js') }}"></script>
@endpush
