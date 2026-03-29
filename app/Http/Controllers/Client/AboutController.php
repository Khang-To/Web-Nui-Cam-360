<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebImage;

class AboutController extends Controller
{
    // Hiển thị trang Giới thiệu
    public function index(){
        $bannerAbout = WebImage::where('group', 'banner_about')
                             ->where('is_active', 1)
                             ->latest()
                             ->first();
        return view('client.about', compact('bannerAbout'));
    }
}
