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
        Schema::create('scenes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path'); // ảnh 360

            $table->float('initial_yaw')->nullable();
            $table->float('initial_pitch')->nullable();
            $table->float('initial_fov')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_start')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenes');
    }
};
