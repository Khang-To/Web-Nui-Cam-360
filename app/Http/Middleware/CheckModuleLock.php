<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WebSetting; // Gọi Model của bạn vào

class CheckModuleLock
{
    /**
     * moduleKey sẽ là tên của setting mà bạn đã tạo trong database, ví dụ: 'lock_virtual_tour', 'lock_locations', 'lock_about'
      * Bạn sẽ dùng middleware này trong route như sau:
      * Route::get('/virtual-tour', [VirtualTourController::class, 'index'])->middleware('check.module.lock:lock_virtual_tour');
     **/
    public function handle(Request $request, Closure $next, $moduleKey): Response
    {
        // Dùng hàm get() xịn xò của bạn để kiểm tra. Nếu giá trị = 1 nghĩa là đang khóa.
        if (WebSetting::get($moduleKey) == '1') {

            // Trả về một trang thông báo bảo trì (Mã lỗi 503)
            return response()->view('errors.maintenance', [
                'moduleName' => $this->getModuleName($moduleKey)
            ], 503);
        }

        // Nếu không khóa thì cho qua bình thường
        return $next($request);
    }

    // Hàm phụ để dịch cái key thành chữ Tiếng Việt cho thông báo đẹp hơn
    private function getModuleName($key) {
        $names = [
            'lock_virtual_tour' => 'Tour 360 độ',
            'lock_locations'    => 'Danh sách Địa điểm',
            'lock_about'        => 'Trang Giới thiệu'
        ];
        return $names[$key] ?? 'Trang này';
    }
}
