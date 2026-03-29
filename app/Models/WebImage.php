<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebImage extends Model
{
    use HasFactory;
    // Chỉ quản lý hình ảnh cho 'carousel' và 'banner'
    protected $fillable = [
        'group',        // Phân loại: 'carousel' hoặc 'banner'
        'image_path',   // Đường dẫn ảnh
        'title',        // Tiêu đề
        'description',  // Mô tả dành cho carosel
        'is_active',    // Trạng thái: Bật/Tắt
        'order'         // Thứ tự sắp xếp
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    // Accessor để lấy URL của ảnh
    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }

        return asset('images/no-image.jpg');
    }

    // Scope lấy ảnh đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
