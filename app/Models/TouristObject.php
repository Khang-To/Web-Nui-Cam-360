<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TouristObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'description',
        'location_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function hotspots()
    {
        return $this->hasMany(Hotspot::class);
    }

    // Accessor để lấy URL của ảnh
    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }

        return asset('images/no-image.jpg');
    }
}
