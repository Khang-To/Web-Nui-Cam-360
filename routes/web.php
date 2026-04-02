<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\SceneController;
use App\Http\Controllers\Admin\TouristObjectController;
use App\Http\Controllers\Admin\HotspotController;
use App\Http\Controllers\Admin\WebImageController;
use App\Http\Controllers\Admin\WebSettingController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\LocationController as ClientLocationController; // Thêm alias để tránh trùng tên với LocationController của admin
use App\Http\Controllers\Client\VirtualTourController;
use App\Http\Controllers\Client\AboutController;

// ==========================================
// ROUTES CHO GIAO DIỆN NGƯỜI DÙNG (CLIENT)
// ==========================================

// Gọi thẳng vào hàm index của HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

// ĐÃ THÊM: Route trỏ về trang Giới thiệu
Route::get('/gioi-thieu', [AboutController::class, 'index'])->name('client.about');

// Route Trang Danh sách Thắng cảnh
Route::get('/danh-thang', [ClientLocationController::class, 'index'])->name('client.location.index');

// Route Trang Chi tiết Thắng cảnh
Route::get('/danh-thang/{id}', [ClientLocationController::class, 'detail'])->name('client.location.detail');

// Route Trang Tour 360
Route::get('/virtual-tour', [VirtualTourController::class, 'index'])
    ->name('client.virtualtour.index');

// Route AJAX load scene
Route::get('/virtual-tour/scene/{scene}', [VirtualTourController::class, 'getScene'])
    ->name('client.virtualtour.scene');

// Route xử lý việc gửi form đánh giá
Route::post('/gioi-thieu/gui-danh-gia', [AboutController::class, 'store'])->name('client.reviews.store');

// ==========================================
// ROUTES CHO TRANG QUẢN TRỊ (ADMIN)
// ==========================================

// Routes không cần đăng nhập
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Routes cần đăng nhập
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Đổi mật khẩu
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.update');

    // Quản lý địa điểm
    Route::resource('locations', LocationController::class);

    // Quản lý đối tượng du lịch
    Route::resource('tourist_objects', TouristObjectController::class);

    // Quản lý scene panorama
    Route::resource('scenes', SceneController::class);

    // Thêm route để quản lý hotspot của scene
    Route::get(
        'scenes/{scene}/hotspots',
        [SceneController::class, 'hotspots']
    )->name('scenes.hotspots');

    // Thêm route để cập nhật góc nhìn ban đầu của scene
    Route::post(
        'scenes/{scene}/set-initial-view',
        [SceneController::class, 'setInitialView']
    )->name('scenes.setInitialView');

    // Quản lý hotspot
    Route::post('/hotspots', [HotspotController::class, 'store']);
    Route::put('/hotspots/{hotspot}', [HotspotController::class, 'update']);
    Route::delete('/hotspots/{hotspot}', [HotspotController::class, 'destroy']);

    // ================= QUẢN LÝ TRANG WEB =================

    // 1. Quản lý Hình ảnh (Carousel, Banner)
    Route::resource('web_images', WebImageController::class)->except(['show']);
    Route::patch('web_images/{web_image}/toggle-active', [WebImageController::class, 'toggleActive'])->name('web_images.toggle_active');

    // 2. Cấu hình chung (Chỉ cần trang hiển thị form và 1 hàm lưu)
    Route::get('web_settings', [WebSettingController::class, 'index'])->name('web_settings.index');
    Route::post('web_settings', [WebSettingController::class, 'store'])->name('web_settings.store');

    // 3. Đánh giá khách hàng (Admin chỉ cần xem danh sách, duyệt/ẩn và xóa)
    Route::resource('reviews', ReviewController::class)->only(['index', 'update', 'destroy', 'show']);
});

Route::post(
    '/admin/upload-editor-image',
    [LocationController::class, 'uploadEditorImage']
)->name('admin.upload.editor.image');
