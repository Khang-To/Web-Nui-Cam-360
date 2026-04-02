<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebSettingController extends Controller
{
    // 1. HIỂN THỊ TRANG CẤU HÌNH
    public function index()
    {
        // Lấy tất cả setting hiện có và map thành mảng $settings['key'] = 'value'
        // Đồng thời xử lý decode JSON y như hàm get() trong Model của bạn
        $allSettings = WebSetting::all();
        $settings = [];

        foreach ($allSettings as $item) {
            $decoded = json_decode($item->value, true);
            $settings[$item->key] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $item->value;
        }

        return view('admin.web_settings.index', compact('settings'));
    }

    // 2. LƯU CẤU HÌNH
    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        // 1. Xử lý các trường text được gom trong mảng settings[...]
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                // Dùng hàm set() cực mượt của bạn để lưu
                WebSetting::set($key, $value);
            }
        }

        // 2. Xử lý Upload Logo
        if ($request->hasFile('logo')) {
            // Dùng hàm get() của bạn để lấy logo cũ mang đi xóa
            $oldLogo = WebSetting::get('logo');

            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Lưu file mới và dùng hàm set() để cập nhật DB
            $path = $request->file('logo')->store('settings', 'public');
            WebSetting::set('logo', $path);
        }

        return back()->with('success', 'Đã cập nhật cấu hình website thành công!');
    }
}
