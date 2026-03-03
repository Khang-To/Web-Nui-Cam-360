<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotspots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('scene_id')
                ->constrained('scenes')
                ->cascadeOnDelete(); // xóa scene thì xóa hotspot

            $table->enum('type', ['link', 'info']);

            $table->float('yaw');
            $table->float('pitch');
            $table->float('rotation')->nullable();

            // LINK hotspot
            $table->foreignId('target_scene_id')
                ->nullable()
                ->constrained('scenes')
                ->nullOnDelete(); // không cascade

            $table->float('target_yaw')->nullable();
            $table->float('target_pitch')->nullable();
            $table->float('target_fov')->nullable();

            // INFO hotspot
            $table->foreignId('tourist_object_id')
                ->nullable()
                ->constrained('tourist_objects')
                ->nullOnDelete(); // không cascade

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspots');
    }
};
