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

    /**
     * Accessor tạo thuộc tính 'masked_email' (Che một phần email)
     * Cách dùng: $review->masked_email
     */
    public function getMaskedEmailAttribute()
    {
        $parts = explode('@', $this->email);

        // Nếu không đúng định dạng email thì trả về nguyên gốc (phòng hờ)
        if (count($parts) !== 2) return $this->email;

        $name = $parts[0];
        $domain = $parts[1];
        $len = strlen($name);

        // Che email: Lấy 3 chữ cái đầu, thay khúc giữa bằng ***, giữ lại chữ cái cuối cùng
        if ($len <= 3) {
            $maskedName = substr($name, 0, 1) . '***';
        } else {
            $maskedName = substr($name, 0, 3) . '***' . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}
