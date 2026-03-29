<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Bảng 'reviews' cần các cột: 'id', 'name', 'email', 'rating', 'content', 'is_approved'
    protected $fillable = [
        'name',
        'email',
        'rating',
        'content',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating'      => 'integer',
    ];

    // Scope lấy những đánh giá đã được duyệt ra ngoài trang chủ
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
