<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema; // Import Schema cho gọn
use Illuminate\Support\Facades\View;   // Import View để share dữ liệu
use App\Models\WebSetting;             // Import Model WebSetting

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Cấu hình mặc định của bạn
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // 2. CẤU HÌNH GLOBAL SETTINGS
        // Kiểm tra xem bảng 'web_settings' đã tồn tại chưa
        // (Tránh văng lỗi khi bạn vừa clone code về máy mới mà chưa kịp chạy migrate)
        if (Schema::hasTable('web_settings')) {

            $allSettings = WebSetting::all();
            $settings = [];

            // Xử lý vòng lặp y hệt như Controller để decode JSON an toàn
            foreach ($allSettings as $item) {
                $decoded = json_decode($item->value, true);
                $settings[$item->key] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $item->value;
            }

            // Chia sẻ biến $globalSettings cho TOÀN BỘ các file Blade
            View::share('globalSettings', $settings);
        }
    }
}
