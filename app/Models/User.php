<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Các cột được phép insert/update
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * Các cột ẩn khi trả về JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tự động hash password
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
