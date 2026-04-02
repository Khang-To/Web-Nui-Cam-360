<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validate ngày tháng
        $request->validate([
            'start_date' => 'nullable|date|required_with:end_date',
            'end_date'   => 'nullable|date|required_with:start_date|after_or_equal:start_date',
        ], [
            'start_date.required_with' => 'Bạn phải chọn cả ngày bắt đầu và ngày kết thúc!',
            'end_date.required_with'   => 'Bạn phải chọn cả ngày bắt đầu và ngày kết thúc!',
            'end_date.after_or_equal'  => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu!',
        ]);

        // 2. Dữ liệu cố định (Không bị ảnh hưởng bởi bộ lọc)
        $pendingReviewCount = Review::where('is_approved', false)->count();
        $pendingReviews = Review::where('is_approved', false)->latest()->take(5)->get();

        // 3. Query bảng thống kê (Có ảnh hưởng bởi bộ lọc)
        $query = Review::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalReviews = $query->count();
        $averageRating = $totalReviews > 0 ? round($query->avg('rating'), 1) : 0;

        $starCounts = [
            5 => (clone $query)->where('rating', 5)->count(),
            4 => (clone $query)->where('rating', 4)->count(),
            3 => (clone $query)->where('rating', 3)->count(),
            2 => (clone $query)->where('rating', 2)->count(),
            1 => (clone $query)->where('rating', 1)->count(),
        ];

        // ==========================================
        // THÊM MỚI: XỬ LÝ NẾU LÀ AJAX REQUEST
        // ==========================================
        if ($request->ajax()) {
            $percentages = [];
            for ($i = 5; $i >= 1; $i--) {
                $percentages[$i] = $totalReviews > 0 ? ($starCounts[$i] / $totalReviews) * 100 : 0;
            }

            return response()->json([
                'success'       => true,
                'totalReviews'  => $totalReviews,
                'averageRating' => $averageRating,
                'starCounts'    => $starCounts,
                'percentages'   => $percentages
            ]);
        }

        return view('admin.dashboard', compact(
            'pendingReviewCount',
            'pendingReviews',
            'totalReviews',
            'averageRating',
            'starCounts'
        ));
    }
}
