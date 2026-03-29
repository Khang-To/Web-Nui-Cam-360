<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\WebImage; // BƯỚC 1: Đừng quên import Model này nhé!

class LocationController extends Controller
{
    /**
     * Hiển thị trang danh sách TẤT CẢ thắng cảnh
     */
    public function index()
    {
        // 1. Lấy danh sách địa điểm
        $locations = Location::latest()->paginate(9);

        // 2. Lấy Banner cho trang danh sách (Lấy 1 tấm đang bật)
        $bannerLocations = WebImage::where('group', 'banner_location')
                                   ->where('is_active', 1)
                                   ->latest()
                                   ->first();

        // 3. Truyền thêm biến $bannerLocations ra view
        return view('client.locations.index', compact('locations', 'bannerLocations'));
    }

    /**
     * Hiển thị trang CHI TIẾT 1 thắng cảnh
     */
    public function detail($id)
    {
        // 1. Lấy chi tiết địa điểm hiện tại
        $location = Location::findOrFail($id);

        // 2. TÌM ĐỊA ĐIỂM TIẾP THEO
        $nextLocation = Location::where('id', '>', $location->id)->orderBy('id', 'asc')->first();

        // NẾU LÀ BÀI CUỐI CÙNG: Vòng lại lấy bài đầu tiên
        if (!$nextLocation) {
            $nextLocation = Location::orderBy('id', 'asc')->first();
        }

        // 3. Lấy Banner quảng cáo cho trang chi tiết (nếu có)
        $bannerDetail = WebImage::where('group', 'banner_location_detail')
                                ->where('is_active', 1)
                                ->first();

        // 4. Truyền thêm biến $bannerDetail ra view
        return view('client.locations.detail', compact('location', 'nextLocation', 'bannerDetail'));
    }
}
