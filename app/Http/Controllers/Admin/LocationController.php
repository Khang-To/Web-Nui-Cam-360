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
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'short_description' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'short_description' => $request->short_description,
            'content' => $request->content,
        ];

        // Nếu có ảnh mới, xóa ảnh cũ và lưu ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if (
                $location->image_path &&
                Storage::disk('public')->exists($location->image_path)
            ) {
                Storage::disk('public')->delete($location->image_path);
            }

            // Lưu ảnh mới
            $data['image_path'] = $request->file('image')
                ->store('locations', 'public');
        }

        $location->update($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Cập nhật địa điểm thành công!');
    }

    /**
     * Xóa
     */
    public function destroy(Location $location)
    {
        if (
            $location->image_path &&
            Storage::disk('public')->exists($location->image_path)
        ) {
            Storage::disk('public')->delete($location->image_path);
        }

        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Xóa địa điểm thành công!');
    }
}
