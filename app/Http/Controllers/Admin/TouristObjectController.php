<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TouristObject;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TouristObjectController extends Controller
{
    /**
     * Danh sách các đối tượng du lịch
     */
    public function index(Request $request)
    {
        $query = TouristObject::with('location');

        // Tìm kiếm theo tên
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo địa điểm
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $touristObjects = $query
            ->latest()
            ->paginate(10)
            ->appends([
                'keyword' => $request->keyword,
                'location_id' => $request->location_id
            ]);

        $locations = Location::all();

        return view('admin.tourist_objects.index', compact('touristObjects', 'locations'));
    }

    /**
     * Form thêm mới
     */
    public function create()
    {
        $locations = Location::all();
        return view('admin.tourist_objects.create', compact('locations'));
    }

    /**
     * Lưu thêm mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'required|string',
            'location_id' => 'required|exists:locations,id',
        ], [
            'name.required' => 'Bạn chưa nhập tên đối tượng du lịch.',
            'description.required' => 'Bạn chưa nhập mô tả.',
            'location_id.required' => 'Bạn chưa chọn địa điểm.',
            'location_id.exists' => 'Địa điểm không tồn tại.',
            'image.image' => 'File tải lên phải là hình ảnh.',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('tourist_objects', 'public');
        }

        TouristObject::create([
            'name' => $request->name,
            'image_path' => $imagePath,
            'description' => $request->description,
            'location_id' => $request->location_id,
        ]);

        return redirect()->route('admin.tourist_objects.index')
            ->with('success', 'Thêm đối tượng du lịch thành công!');
    }

    /**
     * Hiển thị chi tiết
     */
    public function show(TouristObject $touristObject)
    {
        return view('admin.tourist_objects.show', compact('touristObject'));
    }

    /**
     * Form sửa
     */
    public function edit(TouristObject $touristObject)
    {
        $locations = Location::all();
        return view('admin.tourist_objects.edit', compact('touristObject', 'locations'));
    }

    /**
     * Cập nhật
     */
    public function update(Request $request, TouristObject $touristObject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'description' => 'required|string',
            'location_id' => 'required|exists:locations,id',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'location_id' => $request->location_id,
        ];

        // Xử lý ảnh
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if (
                $touristObject->image_path &&
                Storage::disk('public')->exists($touristObject->image_path)
            ) {
                Storage::disk('public')->delete($touristObject->image_path);
            }

            // Lưu ảnh mới
            $data['image_path'] = $request->file('image')
                ->store('tourist_objects', 'public');
        }

        $touristObject->update($data);

        return redirect()->route('admin.tourist_objects.index')
            ->with('success', 'Cập nhật đối tượng du lịch thành công!');
    }

    /**
     * Xóa
     */
    public function destroy(TouristObject $touristObject)
    {
        // Xóa ảnh nếu tồn tại
        if (
            $touristObject->image_path &&
            Storage::disk('public')->exists($touristObject->image_path)
        ) {
            Storage::disk('public')->delete($touristObject->image_path);
        }

        $touristObject->delete();

        return redirect()->route('admin.tourist_objects.index')
            ->with('success', 'Xóa đối tượng du lịch thành công!');
    }
}
