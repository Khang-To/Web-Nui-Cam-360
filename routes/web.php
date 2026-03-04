<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LocationController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('locations', LocationController::class);
});

Route::post('/admin/upload-editor-image',
    [LocationController::class, 'uploadEditorImage']
)->name('admin.upload.editor.image');
