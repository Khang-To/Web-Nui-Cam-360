<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá kèm tính năng Lọc (Filter)
     */
    public function index(Request $request)
    {
        // ==========================================
        // 1. RÀNG BUỘC NGÀY THÁNG (BACK-END)
        // ==========================================
        $request->validate([
            'start_date' => 'nullable|date|required_with:end_date',
            'end_date'   => 'nullable|date|required_with:start_date|after_or_equal:start_date',
        ], [
            'start_date.required_with' => 'Bạn phải chọn cả ngày bắt đầu và ngày kết thúc!',
            'end_date.required_with'   => 'Bạn phải chọn cả ngày bắt đầu và ngày kết thúc!',
            'end_date.after_or_equal'  => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu!',
        ]);

        $query = Review::query();

        // 2. Lọc theo trạng thái hiển thị
        if ($request->filled('status')) {
            if ($request->status == 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status == 'pending') {
                $query->where('is_approved', false);
            }
        }

        // 3. Lọc theo số sao
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // 4. Lọc theo khoảng thời gian
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Lấy danh sách phân trang (10 bản ghi/trang)
        $reviews = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.reviews.index', compact('reviews'));
    }

    // 2. TRANG CHI TIẾT
    public function show(Review $review)
    {
        return view('admin.reviews.show', compact('review'));
    }

    // Cập nhật trạng thái duyệt của đánh giá (Duyệt hoặc Ẩn)
    public function update(Request $request, Review $review)
    {
        // Kiểm tra xem request có gửi lên is_approved cụ thể không (từ Dashboard)
        // Nếu không có, thì tự động đảo ngược trạng thái (từ nút Toggle bên trang Index)
        $newStatus = $request->has('is_approved') ? $request->is_approved : !$review->is_approved;

        $review->update([
            'is_approved' => $newStatus
        ]);

        $message = $review->is_approved ? 'Đã duyệt hiển thị đánh giá này!' : 'Đã ẩn đánh giá khỏi trang chủ!';

        // Nếu là request từ AJAX (Fetch API bên trang Index)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'is_approved' => $review->is_approved,
                'message'     => $message
            ]);
        }

        // Nếu là request từ Form bình thường (Bên trang Dashboard)
        return back()->with('success', $message);
    }

    // Xóa đánh giá vĩnh viễn
    public function destroy(Request $request, Review $review)
    {
        $review->delete();

        // Nếu là request từ AJAX (Bên trang Index)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đánh giá vĩnh viễn!'
            ]);
        }

        // Nếu là request từ Form bình thường (Bên trang Dashboard)
        return back()->with('success', 'Đã xóa đánh giá vĩnh viễn!');
    }
}
