<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\TouristObjectController;

Route::get('/', function () {
    return view('welcome');
});

// Routes không cần đăng nhập
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Routes cần đăng nhập
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Đổi mật khẩu
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password.update');

    // Quản lý địa điểm
    Route::resource('locations', LocationController::class);

    // Quản lý đối tượng du lịch
    Route::resource('tourist_objects', TouristObjectController::class);
});

Route::post('/admin/upload-editor-image',
    [LocationController::class, 'uploadEditorImage']
)->name('admin.upload.editor.image');
