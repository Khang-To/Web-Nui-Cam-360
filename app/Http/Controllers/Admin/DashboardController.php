<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review; // Chỉ cần use Review vì các Model kia Khang đang gọi thẳng trong Blade rồi

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy số lượng đánh giá đang chờ duyệt
        $pendingReviewCount = Review::where('is_approved', false)->count();

        // Lấy 5 đánh giá mới nhất đang chờ duyệt
        $pendingReviews = Review::where('is_approved', false)
                                ->latest()
                                ->take(5)
                                ->get();

        // Trả về view kèm dữ liệu
        return view('admin.dashboard', compact('pendingReviewCount', 'pendingReviews'));
    }
}
