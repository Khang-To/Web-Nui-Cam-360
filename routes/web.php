<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    // Gọi file dashboard.blade.php nằm trong thư mục resources/views/admin/
    return view('admin.dashboard');
});
