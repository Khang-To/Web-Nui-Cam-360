<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scene extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'initial_yaw',
        'initial_pitch',
        'initial_fov',
        'is_default',
        'is_start',
    ];

    protected $casts = [
        'is_default' => 'boolean',  // dùng để đánh dấu scene nào sẽ được hiển thị đầu tiên khi người dùng truy cập vào tour
        'is_start'   => 'boolean',  // dùng để đánh dấu scene nào sẽ được hiển thị trên menu giúp người dùng nhảy cóc tới nơi muốn đến
        'initial_yaw' => 'float',
        'initial_pitch' => 'float',
        'initial_fov' => 'float',
    ];

    // một scene có nhiều hotspot
    public function hotspots()
    {
        return $this->hasMany(Hotspot::class);
    }

    // Accessor lấy URL ảnh (dùng trong bảng, trang xem)
    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }

        return asset('images/no-image.jpg');
    }
}
