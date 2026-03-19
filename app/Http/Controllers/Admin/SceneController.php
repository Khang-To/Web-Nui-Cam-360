<?php

namespace App\Http\Controllers\Admin;

use App\Models\TouristObject;
use App\Models\Hotspot; // 👈 ĐÃ THÊM MODEL HOTSPOT VÀO ĐÂY
use App\Http\Controllers\Controller;
use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SceneController extends Controller
{
    /**
     * Hiển thị danh sách scene
     */
    public function index(Request $request)
    {
        // Bắt đầu truy vấn từ model Scene
        $query = Scene::query();

        // Nếu có từ khóa tìm kiếm thì lọc theo tên scene
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Sắp xếp scene mới nhất và phân trang 10 bản ghi
        $scenes = $query
            ->orderBy("id")
            ->paginate(10)
            ->appends(['keyword' => $request->keyword]);

        // Trả dữ liệu về view
        return view('admin.scenes.index', compact('scenes'));
    }

    /**
     * Hiển thị form thêm scene
     */
    public function create()
    {
        return view('admin.scenes.create');
    }

    /**
     * Lưu scene mới vào database
     */
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:20480',
        ], [
            'name.required' => 'Bạn chưa nhập tên scene.',
            'image.required' => 'Bạn chưa chọn file ảnh panorama.',
            'image.image' => 'File tải lên phải là hình ảnh.',
        ]);

        /**
         * Kiểm tra ảnh có đúng định dạng panorama 360 hay không
         * Ảnh 360 equirectangular thường có tỉ lệ 2:1
         */
        if ($request->hasFile('image')) {

            $img = $request->file('image');

            // Lấy kích thước ảnh
            $size = @getimagesize($img->getRealPath());

            if ($size) {

                $width = $size[0];
                $height = $size[1];

                // Kiểm tra tỉ lệ gần bằng 2:1
                if (abs(($width / $height) - 2) > 0.01) {

                    return back()
                        ->withErrors([
                            'image' => 'Ảnh phải có định dạng panorama 360 (tỉ lệ 2:1).'
                        ])
                        ->withInput();
                }
            }
        }

        // Biến lưu đường dẫn ảnh
        $path = null;

        // Nếu có upload ảnh thì nén và lưu
        if ($request->hasFile('image')) {
            $path = $this->compressAndStore($request->file('image'));
        }

        // Lấy trạng thái checkbox
        $isDefault = $request->boolean('is_default'); // scene xuất phát
        $isStart = $request->boolean('is_start'); // scene hiển thị menu

        /**
         * Nếu admin chọn scene này là điểm xuất phát
         * thì phải bỏ trạng thái default của scene cũ
         */
        if ($isDefault) {
            Scene::where('is_default', true)->update([
                'is_default' => false
            ]);
        }

        // Tạo scene mới
        $scene = Scene::create([
            'name' => $request->name,
            'image_path' => $path,
            'initial_yaw' => null,
            'initial_pitch' => null,
            'initial_fov' => null,
            'is_default' => $isDefault,
            'is_start' => $isStart,
        ]);

        /**
         * Nếu sau khi thêm scene mà chưa có scene nào là default
         * thì lấy scene có id nhỏ nhất làm điểm xuất phát
         */
        if (!Scene::where('is_default', true)->exists()) {

            $firstScene = Scene::orderBy('id')->first();

            if ($firstScene) {
                $firstScene->update([
                    'is_default' => true
                ]);
            }
        }

        return redirect()
            ->route('admin.scenes.index')
            ->with('success', 'Thêm scene thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa scene
     */
    public function edit(Scene $scene)
    {
        return view('admin.scenes.edit', compact('scene'));
    }

    /**
     * Cập nhật thông tin scene
     */
    public function update(Request $request, Scene $scene)
    {
        // Kiểm tra dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Bạn chưa nhập tên scene.',
        ]);

        // Lấy trạng thái checkbox
        $isDefault = $request->boolean('is_default');
        $isStart = $request->boolean('is_start');

        /**
         * Nếu admin chọn scene này là default
         * thì bỏ default của scene trước đó
         */
        if ($isDefault) {
            Scene::where('is_default', true)->update([
                'is_default' => false
            ]);
        }

        // Cập nhật dữ liệu
        $scene->update([
            'name' => $request->name,
            'is_default' => $isDefault,
            'is_start' => $isStart,
        ]);

        /**
         * Đảm bảo hệ thống luôn có ít nhất 1 scene xuất phát
         */
        if (!Scene::where('is_default', true)->exists()) {

            $firstScene = Scene::orderBy('id')->first();

            if ($firstScene) {
                $firstScene->update([
                    'is_default' => true
                ]);
            }
        }

        return redirect()
            ->route('admin.scenes.index')
            ->with('success', 'Cập nhật scene thành công!');
    }

    /**
     * Xóa scene
     */
    public function destroy(Scene $scene)
    {
        // 1. CHẶN LỖI LIÊN KẾT: Cập nhật các hotspot ở Scene khác đang trỏ tới Scene này thành NULL
        Hotspot::where('target_scene_id', $scene->id)->update([
            'target_scene_id' => null,
            'target_yaw' => null,
            'target_pitch' => null,
            'target_fov' => null
        ]);

        // 2. DỌN RÁC: Xóa luôn tất cả các Hotspot nằm bên trong Scene này
        Hotspot::where('scene_id', $scene->id)->delete();

        // Lưu lại trạng thái scene có phải default không
        $wasDefault = $scene->is_default;

        /**
         * Nếu có ảnh panorama thì xóa khỏi storage
         */
        if ($scene->image_path && Storage::disk('public')->exists($scene->image_path)) {
            Storage::disk('public')->delete($scene->image_path);
        }

        // Xóa scene khỏi database
        $scene->delete();

        /**
         * Nếu scene bị xóa là scene xuất phát
         * thì chọn scene có id nhỏ nhất làm scene xuất phát mới
         */
        if ($wasDefault) {

            $newDefault = Scene::orderBy('id')->first();

            if ($newDefault) {
                $newDefault->update([
                    'is_default' => true
                ]);
            }
        }

        return redirect()
            ->route('admin.scenes.index')
            ->with('success', 'Xóa scene thành công!');
    }

    /**
     * Chức năng cấu hình hotspot cho scene
     */
    public function hotspots(Scene $scene)
    {
        // load hotspot + quan hệ
        $scene->load([
            'hotspots.targetScene',
            'hotspots.touristObject'
        ]);

        // danh sách scene (dropdown)
        $scenes = Scene::orderBy('id')->get();

        // danh sách đối tượng du lịch (dropdown)
        $touristObjects = TouristObject::orderBy('name')->get();

        return view('admin.scenes.hotspots', compact(
            'scene',
            'scenes',
            'touristObjects'
        ));
    }

    /**
     * Nén ảnh panorama và lưu vào storage
     */
    protected function compressAndStore($file)
    {
        // Lấy phần mở rộng của file
        $extension = strtolower($file->getClientOriginalExtension());

        // Tạo tên file an toàn
        $filename = time() . '_' . preg_replace(
            '/[^a-zA-Z0-9.\-_]/',
            '_',
            $file->getClientOriginalName()
        );

        // Đường dẫn lưu file
        $storagePath = storage_path('app/public/scenes');

        // Nếu thư mục chưa tồn tại thì tạo
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $targetPath = $storagePath . DIRECTORY_SEPARATOR . $filename;

        try {

            /**
             * Nếu là ảnh JPG hoặc JPEG thì nén chất lượng ~80%
             */
            if (in_array($extension, ['jpg', 'jpeg'])) {

                $image = imagecreatefromstring(
                    file_get_contents($file->getRealPath())
                );

                if ($image !== false) {

                    imagejpeg($image, $targetPath, 80);

                    imagedestroy($image);
                } else {

                    $file->move($storagePath, $filename);
                }
            }
            /**
             * Nếu là PNG thì nén mức trung bình
             */
            elseif ($extension === 'png') {

                $image = imagecreatefrompng($file->getRealPath());

                if ($image !== false) {

                    imagepng($image, $targetPath, 6);

                    imagedestroy($image);
                } else {

                    $file->move($storagePath, $filename);
                }
            }
            /**
             * Các định dạng khác thì lưu trực tiếp
             */
            else {

                $file->move($storagePath, $filename);
            }
        } catch (\Throwable $e) {

            // Nếu có lỗi thì lưu file gốc
            $file->move($storagePath, $filename);
        }

        // Trả về đường dẫn lưu trong storage
        return 'scenes/' . $filename;
    }

    // Hàm xử lý cập nhật góc nhìn ban đầu của scene
    public function setInitialView(Request $request, Scene $scene)
    {
        // Kiểm tra dữ liệu đầu vào
        $data = $request->validate([
            'yaw' => 'required|numeric',
            'pitch' => 'required|numeric',
            'fov' => 'required|numeric',
        ]);

        // Cập nhật góc nhìn ban đầu cho scene
        $scene->update([
            'initial_yaw' => $data['yaw'],
            'initial_pitch' => $data['pitch'],
            'initial_fov' => $data['fov'],
        ]);

        // Trả về phản hồi JSON thành công
        return response()->json([
            'success' => true
        ]);
    }
}
