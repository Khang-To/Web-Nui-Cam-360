<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Scene;

class VirtualTourController extends Controller
{
    public function index()
    {
        $defaultScene = Scene::where('is_default', true)->first()
            ?? Scene::first();

        $menuScenes = Scene::where('is_start', true)->get();

        return view('client.VirtualTour.index', [
            'defaultScene' => $defaultScene,
            'menuScenes' => $menuScenes
        ]);
    }

    public function getScene(Scene $scene)
    {
        $scene->load([
            'hotspots.targetScene',
            'hotspots.touristObject'
        ]);

        return response()->json([
            'id' => $scene->id,
            'name' => $scene->name,
            'image' => $scene->image_url,

            'initial_yaw' => (float) $scene->initial_yaw,
            'initial_pitch' => (float) $scene->initial_pitch,
            'initial_fov' => (float) $scene->initial_fov,

            'hotspots' => $scene->hotspots->map(function ($h) {
                return [
                    'type' => $h->type,
                    'yaw' => (float) $h->yaw,
                    'pitch' => (float) $h->pitch,
                    'rotation' => (float) $h->rotation,

                    'target_scene_id' => $h->target_scene_id,
                    'target_scene_name' => optional($h->targetScene)->name,
                    'target_yaw' => (float) $h->target_yaw,
                    'target_pitch' => (float) $h->target_pitch,
                    'target_fov' => (float) $h->target_fov,

                    'tourist_object' => $h->touristObject ? [
                        'name' => $h->touristObject->name,
                        'image' => $h->touristObject->image_url,
                        'description' => $h->touristObject->description,
                    ] : null,
                ];
            })->values()
        ]);
    }
}
