<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotspot;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    /**
     * Tạo hotspot
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'scene_id' => 'required|exists:scenes,id',
            'type' => 'required|in:link,info',

            'yaw' => 'required|numeric',
            'pitch' => 'required|numeric',
            'rotation' => 'nullable|numeric',

            // LINK
            'target_scene_id' => 'nullable|exists:scenes,id',
            'target_yaw' => 'nullable|numeric',
            'target_pitch' => 'nullable|numeric',
            'target_fov' => 'nullable|numeric',

            // INFO
            'tourist_object_id' => 'nullable|exists:tourist_objects,id',
        ]);

        $hotspot = Hotspot::create($data);

        return response()->json([
            'success' => true,
            'hotspot' => $hotspot
        ]);
    }

    /**
     * Cập nhật hotspot
     */
    public function update(Request $request, Hotspot $hotspot)
    {
        $data = $request->validate([
            'type' => 'required|in:link,info',

            'yaw' => 'required|numeric',
            'pitch' => 'required|numeric',
            'rotation' => 'nullable|numeric',

            // LINK
            'target_scene_id' => 'nullable|exists:scenes,id',
            'target_yaw' => 'nullable|numeric',
            'target_pitch' => 'nullable|numeric',
            'target_fov' => 'nullable|numeric',

            // INFO
            'tourist_object_id' => 'nullable|exists:tourist_objects,id',
        ]);

        $hotspot->update($data);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Xóa hotspot
     */
    public function destroy(Hotspot $hotspot)
    {
        $hotspot->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
