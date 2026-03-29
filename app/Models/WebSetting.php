<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    // Bảng 'web_settings' chỉ cần 2 cột: 'key', 'value'
    protected $fillable = ['key', 'value'];

    /**
     * Lấy giá trị cấu hình.
     * WebSetting::get('phone')
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) return $default;

        $decoded = json_decode($setting->value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        return $setting->value;
    }

    /**
     * Lưu giá trị cấu hình.
     * WebSetting::set('phone', '0987654321')
     */
    public static function set($key, $value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
