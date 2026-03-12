<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Hiển thị trang danh sách TẤT CẢ thắng cảnh
     */
    public function index()
    {
        // Dùng paginate(9) để nếu sau này có 20-30 danh thắng thì nó tự chia trang (mỗi trang 9 cái)
        $locations = Location::latest()->paginate(9);

        // Trả về view danh sách
        return view('client.locations.index', compact('locations'));
    }

    /**
     * Hiển thị trang CHI TIẾT 1 thắng cảnh
     */
    public function detail($id)
    {
        $location = Location::findOrFail($id);

        // TÌM ĐỊA ĐIỂM TIẾP THEO: Lấy bài có ID lớn hơn bài hiện tại
        $nextLocation = Location::where('id', '>', $location->id)->orderBy('id', 'asc')->first();

        // NẾU LÀ BÀI CUỐI CÙNG (Không có bài nào ID lớn hơn): Vòng lại lấy bài đầu tiên (ID nhỏ nhất)
        if (!$nextLocation) {
            $nextLocation = Location::orderBy('id', 'asc')->first();
        }

        // Truyền thêm biến $nextLocation sang view
        return view('client.locations.detail', compact('location', 'nextLocation'));
    }
}
