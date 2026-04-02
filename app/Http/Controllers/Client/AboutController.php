<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebImage;
use App\Models\Review; // Gọi thêm Model Review

class AboutController extends Controller
{
    // Hiển thị trang Giới thiệu
    public function index(){
        // Lấy banner
        $bannerAbout = WebImage::where('group', 'banner_about')
                             ->where('is_active', 1)
                             ->latest()
                             ->first();

        // Lấy danh sách đánh giá ĐÃ ĐƯỢC DUYỆT (is_approved = true)
        // Dùng get() để lấy toàn bộ, hoặc take(10)->get() nếu chỉ muốn hiện 10 đánh giá mới nhất
        $approvedReviews = Review::where('is_approved', true)
                                 ->latest()
                                 ->get();

        // Truyền thêm biến $approvedReviews ra View
        return view('client.about', compact('bannerAbout', 'approvedReviews'));
    }

    // Xử lý lưu đánh giá mới từ form
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào (Validation)
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ], [
            'name.required'    => 'Vui lòng nhập tên của bạn.',
            'email.required'   => 'Vui lòng nhập địa chỉ email.',
            'email.email'      => 'Email không đúng định dạng.',
            'content.required' => 'Vui lòng nhập nội dung đánh giá.',
        ]);

        // 2. Lưu vào Database
        // Nhờ migration của bạn có `default(false)`, nên ở đây ta không cần truyền is_approved, nó sẽ tự động nhận false (Chờ duyệt)
        Review::create($validated);

        // 3. Trả về phản hồi JSON cho AJAX xử lý
        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn! Đánh giá của bạn đã được gửi và đang chờ quản trị viên phê duyệt.'
        ]);
    }
}
