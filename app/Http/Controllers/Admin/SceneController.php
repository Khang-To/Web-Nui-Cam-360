<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SceneController extends Controller
{
    /**
     * Display a listing of the scenes.
     */
    public function index(Request $request)
    {
        // bắt đầu xây dựng truy vấn từ model
        $query = Scene::query();

        // nếu có từ khóa tim kiếm, thêm điều kiện LIKE
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // sắp xếp mới nhất, phân trang 10 bản ghi mỗi trang,
        // giữ lại tham số keyword khi chuyển trang
        $scenes = $query
            ->latest()
            ->paginate(10)
            ->appends(['keyword' => $request->keyword]);

        return view('admin.scenes.index', compact('scenes'));
    }

    /**
     * Show the form for creating a new scene.
     */
    public function create()
    {
        return view('admin.scenes.create');
    }

    /**
     * Store a newly created scene in storage.
     */
    public function store(Request $request)
    {
        // kiểm tra dữ liệu đầu vào theo quy tắc
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:20480', // 20MB
        ], [
            'name.required' => 'Bạn chưa nhập tên scene.',
            'image.required' => 'Bạn chưa chọn file ảnh panorama.',
            'image.image' => 'File tải lên phải là hình ảnh.',
        ]);

        // kiểm tra kích thước để đảm bảo ảnh equirectangular 360 độ (tỉ lệ khoảng 2:1)
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $size = @getimagesize($img->getRealPath());
            if ($size) {
                $width = $size[0];
                $height = $size[1];
                // độ lệch tỉ lệ nhỏ hơn 0.01
                if (abs(($width / $height) - 2) > 0.01) {
                    // trả về form với lỗi nếu không phải 360
                    return back()
                        ->withErrors(['image' => 'Ảnh phải có định dạng panorama 360 (tỉ lệ rộng:dài = 2:1).'])
                        ->withInput();
                }
            }
        }

        // khởi tạo biến đường dẫn lưu ảnh
        $path = null;

        if ($request->hasFile('image')) {
            // nén + lưu và nhận lại đường dẫn tương đối
            $path = $this->compressAndStore($request->file('image'));
        }

        Scene::create([
            'name' => $request->name,
            'image_path' => $path,
            'initial_yaw' => null,
            'initial_pitch' => null,
            'initial_fov' => null,
        ]);

        return redirect()->route('admin.scenes.index')
            ->with('success', 'Thêm scene thành công!');
    }

    /**
     * Show the form for editing the specified scene.
     */
    public function edit(Scene $scene)
    {
        return view('admin.scenes.edit', compact('scene'));
    }

    /**
     * Update the specified scene in storage.
     */
    public function update(Request $request, Scene $scene)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ],[
            'name.required' => 'Bạn chưa nhập tên scene.',
        ]);

        // chỉ cập nhật tên, không cho đổi ảnh
        $scene->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.scenes.index')
            ->with('success', 'Cập nhật scene thành công!');
    }

    /**
     * Remove the specified scene from storage.
     */
    public function destroy(Scene $scene)
    {
        // xóa file panorama nếu tồn tại trên disk public
        if ($scene->image_path && Storage::disk('public')->exists($scene->image_path)) {
            Storage::disk('public')->delete($scene->image_path);
        }

        $scene->delete();

        return redirect()->route('admin.scenes.index')
            ->with('success', 'Xóa scene thành công!');
    }

    /**
     * Placeholder for hotspot configuration.
     */
    public function hotspots(Scene $scene)
    {
        // chưa cài đặt
        return redirect()->route('admin.scenes.index')
            ->with('info', 'Chức năng cấu hình hotspot đang phát triển.');
    }

    /**
     * Compress image (quality ~80%) and store under public/scenes.
     * Returns relative path that can be used for storage.
     */
    protected function compressAndStore($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $file->getClientOriginalName());
        $storagePath = storage_path('app/public/scenes');

        // đảm bảo thư mục scenes tồn tại
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $targetPath = $storagePath . DIRECTORY_SEPARATOR . $filename;

        try {
            // Use GD to re-encode and compress the image
            // xử lý nén tùy theo định dạng ảnh
            if (in_array($extension, ['jpg', 'jpeg'])) {
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($image !== false) {
                    // ghi với chất lượng 80%
                    imagejpeg($image, $targetPath, 80);
                    imagedestroy($image);
                } else {
                    // nếu GD không mở được, chỉ chuyển file gốc
                    $file->move($storagePath, $filename);
                }
            } elseif ($extension === 'png') {
                $image = imagecreatefrompng($file->getRealPath());
                if ($image !== false) {
                    // nén mức 6 (vừa phải)
                    imagepng($image, $targetPath, 6);
                    imagedestroy($image);
                } else {
                    $file->move($storagePath, $filename);
                }
            } else {
                // các định dạng khác không cần nén
                $file->move($storagePath, $filename);
            }
        } catch (\Throwable $e) {
            // in case of any error just move original
            $file->move($storagePath, $filename);
        }

        return 'scenes/' . $filename;
    }
}
