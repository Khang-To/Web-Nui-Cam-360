<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebImageController extends Controller
{
    public function index()
    {
        // Sắp xếp theo nhóm trước, rồi mới sắp xếp theo thứ tự
        $webImages = WebImage::orderBy('group')->orderBy('order')->get();
        return view('admin.web_images.index', compact('webImages'));
    }

    public function create()
    {
        // Lấy số thứ tự tiếp theo để hiển thị ra cho đẹp (người dùng không sửa được)
        $maxOrder = WebImage::where('group', 'carousel')->max('order') ?? 0;
        $nextOrder = $maxOrder + 1;

        return view('admin.web_images.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group'       => 'required|string|max:50',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            // Đã xóa validate 'order' vì form không còn gửi trường này lên nữa
        ], [
            'image.required' => 'Vui lòng chọn một hình ảnh.',
            'image.image'    => 'File tải lên phải là hình ảnh hợp lệ.',
            'image.max'      => 'Dung lượng ảnh không được vượt quá 5MB.',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // TỰ ĐỘNG XỬ LÝ SỐ THỨ TỰ
        if ($request->group === 'carousel') {
            $maxOrder = WebImage::where('group', 'carousel')->max('order') ?? 0;
            $data['order'] = $maxOrder + 1;
        } else {
            // Nếu là các loại Banner thì luôn gán order = 0
            $data['order'] = 0;
        }

        // Upload và đổi tên file tự động (không sợ lỗi tiếng Việt hay khoảng trắng)
        if ($request->hasFile('image')) {
            $folderName = 'web_images/' . $request->group;
            $data['image_path'] = $request->file('image')->store($folderName, 'public');
        }

        WebImage::create($data);

        return redirect()->route('admin.web_images.index')
                         ->with('success', 'Đã thêm hình ảnh thành công!');
    }

    public function edit(WebImage $webImage)
    {
        return view('admin.web_images.edit', compact('webImage'));
    }

    public function update(Request $request, WebImage $webImage)
    {
        $request->validate([
            'group'       => 'required|string|max:50',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // XỬ LÝ LOGIC KHI ĐỔI PHÂN LOẠI (Ví dụ: Đổi từ Banner sang Carousel và ngược lại)
        if ($request->group !== $webImage->group) {
            if ($request->group === 'carousel') {
                $maxOrder = WebImage::where('group', 'carousel')->max('order') ?? 0;
                $data['order'] = $maxOrder + 1;
            } else {
                $data['order'] = 0;
                // Dồn lại số thứ tự của các Carousel cũ để không bị trống chỗ
                if ($webImage->group === 'carousel') {
                    WebImage::where('group', 'carousel')
                        ->where('order', '>', $webImage->order)
                        ->decrement('order');
                }
            }
        }

        if ($request->hasFile('image')) {
            // Xóa file cũ trong thư mục
            if ($webImage->image_path && Storage::disk('public')->exists($webImage->image_path)) {
                Storage::disk('public')->delete($webImage->image_path);
            }
            // Lưu file mới
            $folderName = 'web_images/' . $request->group;
            $data['image_path'] = $request->file('image')->store($folderName, 'public');
        }

        $webImage->update($data);

        return redirect()->route('admin.web_images.index')
                         ->with('success', 'Đã cập nhật hình ảnh thành công!');
    }

    public function destroy(WebImage $webImage)
    {
        $deletedGroup = $webImage->group;
        $deletedOrder = $webImage->order;

        if ($webImage->image_path && Storage::disk('public')->exists($webImage->image_path)) {
            Storage::disk('public')->delete($webImage->image_path);
        }

        $webImage->delete();

        // Tự động dồn số nếu xóa ảnh Carousel
        if ($deletedGroup === 'carousel') {
            WebImage::where('group', 'carousel')
                    ->where('order', '>', $deletedOrder)
                    ->decrement('order');
        }

        return redirect()->route('admin.web_images.index')
                         ->with('success', 'Đã xóa hình ảnh thành công!');
    }

    public function toggleActive(WebImage $webImage)
    {
        $webImage->update([
            'is_active' => !$webImage->is_active
        ]);

        $statusText = $webImage->is_active ? 'bật' : 'tắt';
        return back()->with('success', "Đã $statusText hiển thị hình ảnh!");
    }
}
