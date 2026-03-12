<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    /**
     * Danh sách
     */
    public function index(Request $request)
    {
        $query = Location::query();

        // Nếu có từ khóa tìm kiếm
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $locations = $query
            ->latest()
            ->paginate(10)
            ->appends([
                'keyword' => $request->keyword
            ]);

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Form thêm
     */

    public function uploadEditorImage(Request $request)
    {
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            $path = $file->store('editor', 'public');

            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Upload failed'], 400);
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Hiển thị chi tiết
     */
    public function show(Location $location)
    {
        return view('admin.locations.show', compact('location'));
    }

    /**
     * Lưu mới
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'short_description' => 'required|string',
                'content' => 'required|string',
            ],
            [
                'name.required' => 'Bạn chưa nhập tên địa điểm.',
                'short_description.required' => 'Bạn chưa nhập mô tả ngắn.',
                'content.required' => 'Bạn chưa nhập nội dung chi tiết.',
                'image.image' => 'File tải lên phải là hình ảnh.',
            ]
        );

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('locations', 'public');
        }

        Location::create([
            'name' => $request->name,
            'image_path' => $imagePath,
            'short_description' => $request->short_description,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Thêm địa điểm thành công!');
    }

    /**
     * Form sửa
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Cập nhật
     */
    public function update(Request $request, Location $location)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'short_description' => 'required|string',
                'content' => 'required|string',
            ],
            [
                'name.required' => 'Bạn chưa nhập tên địa điểm.',
                'short_description.required' => 'Bạn chưa nhập mô tả ngắn.',
                'content.required' => 'Bạn chưa nhập nội dung chi tiết.',
                'image.image' => 'File tải lên phải là hình ảnh.',
            ]
        );

        $data = [
            'name' => $request->name,
            'short_description' => $request->short_description,
            'content' => $request->content,
        ];

        // 1. CẬP NHẬT ẢNH ĐẠI DIỆN: Có ảnh mới -> Xóa ảnh cũ -> Lưu ảnh mới
        if ($request->hasFile('image')) {
            if (!empty($location->image_path) && Storage::disk('public')->exists($location->image_path)) {
                Storage::disk('public')->delete($location->image_path);
            }
            $data['image_path'] = $request->file('image')->store('locations', 'public');
        }

        // 2. DỌN RÁC ẢNH TINYMCE BỊ XÓA KHI SỬA BÀI
        $oldEditorImages = $this->extractEditorImagePaths($location->content); // Ảnh trước khi sửa
        $newEditorImages = $this->extractEditorImagePaths($request->content);  // Ảnh sau khi sửa

        // Tìm những ảnh bị dư ra (có ở bài cũ nhưng không có ở bài mới)
        $imagesToDelete = array_diff($oldEditorImages, $newEditorImages);

        // Xóa những ảnh thừa đó khỏi Storage
        foreach ($imagesToDelete as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $location->update($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Cập nhật địa điểm thành công!');
    }

    /**
     * Hàm hỗ trợ bóc tách đường dẫn ảnh từ nội dung HTML (TinyMCE)
     */
    private function extractEditorImagePaths($content)
    {
        if (!$content) return [];

        // Tìm tất cả các link trong thẻ <img src="...">
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);
        $urls = $matches[1] ?? [];
        $paths = [];

        foreach ($urls as $url) {
            // Tìm vị trí của chuỗi '/storage/' trong đường dẫn
            if (($pos = strpos($url, '/storage/')) !== false) {
                // Cắt lấy toàn bộ phần nằm sau '/storage/'
                // Ví dụ: /storage/editor/anh.jpg -> editor/anh.jpg
                $path = substr($url, $pos + strlen('/storage/'));

                // Tránh việc bị dính các query string (như ?v=123) nếu có
                $path = explode('?', $path)[0];

                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Xóa địa điểm
     */
    public function destroy(Location $location)
    {
        // 1. XÓA ẢNH ĐẠI DIỆN CHÍNH TRONG STORAGE
        if (!empty($location->image_path) && Storage::disk('public')->exists($location->image_path)) {
            Storage::disk('public')->delete($location->image_path);
        }

        // 2. BÓC TÁCH VÀ XÓA TẤT CẢ ẢNH TINYMCE NẰM TRONG CỘT 'CONTENT'
        $editorImages = $this->extractEditorImagePaths($location->content);

        foreach ($editorImages as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // 3. CUỐI CÙNG MỚI XÓA DÒNG DỮ LIỆU TRONG DATABASE
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Đã xóa địa điểm thành công!');
    }
}
