<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'scene_id',
        'type',
        'yaw',
        'pitch',
        'rotation',
        'target_scene_id',
        'target_yaw',
        'target_pitch',
        'target_fov',
        'tourist_object_id',
    ];

    // hotspot thuộc về một scene
    public function scene()
    {
        return $this->belongsTo(Scene::class);
    }

    // hotspot có thể liên kết đến một scene khác (nếu là hotspot chuyển cảnh)
    public function targetScene()
    {
        return $this->belongsTo(Scene::class, 'target_scene_id');
    }

    // hotspot có thể liên kết đến một đối tượng du lịch (nếu là hotspot thông tin)
    public function touristObject()
    {
        return $this->belongsTo(TouristObject::class);
    }
}
