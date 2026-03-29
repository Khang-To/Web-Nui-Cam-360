<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\WebImage;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Lấy 3 địa điểm danh thắng
        $danhThangs = Location::first()->take(3)->get();

        // 2. Lấy danh sách Carousel (Thư viện ảnh)
        $carousels = WebImage::where('group', 'carousel')
                             ->where('is_active', 1)
                             ->orderBy('order')
                             ->get();

        // 3. Lấy ảnh Banner tĩnh cho phần đầu trang chủ (Hero Section)
        $bannerHome = WebImage::where('group', 'banner_home')
                              ->where('is_active', 1)
                              ->latest()
                              ->first();

        // Truyền cả 3 biến ra view
        return view('client.home', compact('danhThangs', 'carousels', 'bannerHome'));
    }
}
