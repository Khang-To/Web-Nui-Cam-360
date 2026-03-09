@extends('layouts.admin')

@section('title', 'Bảng điều khiển - Núi Cấm 360')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-house-door-fill text-primary"></i>
                <strong>Bảng điều khiển</strong>
            </h1>
            <p class="text-muted mb-0">Tổng quan hệ thống quản lý tour 360° Núi Cấm</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Hôm nay: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</small>
        </div>
    </div>

    {{-- Thống kê --}}
    <div class="row mb-4">
        {{-- Địa điểm --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Địa điểm du lịch
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Location::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-geo-alt-fill fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Đối tượng du lịch --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đối tượng tham quan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\TouristObject::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-star-fill fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hotspots --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Điểm Hotspot
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Hotspot::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-record-circle-fill fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scenes --}}
        <div class="col-12 col-md-6 col-xl-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Cảnh quan 360°
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Scene::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-camera-fill fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning-charge-fill text-warning"></i>
                        Hành động nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.locations.create') }}"
                               class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-plus-circle-fill fa-2x mb-2"></i>
                                <span>Thêm địa điểm</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.tourist_objects.create') }}"
                               class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-plus-circle-fill fa-2x mb-2"></i>
                                <span>Thêm đối tượng</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.locations.index') }}"
                               class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-list-ul fa-2x mb-2"></i>
                                <span>Danh sách địa điểm</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.tourist_objects.index') }}"
                               class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center py-4">
                                <i class="bi bi-list-ul fa-2x mb-2"></i>
                                <span>Danh sách đối tượng</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/admin-dashboard.css') }}">
@endpush

