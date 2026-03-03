<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_path',
        'short_description',
        'content',
    ];

    // Một location có nhiều tourist objects
    public function touristObjects()
    {
        return $this->hasMany(TouristObject::class);
    }
}
