<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_images', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index(); // Phân loại: 'carousel' hoặc 'banner' (đánh index để truy vấn cho lẹ)
            $table->string('image_path');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true); // Mặc định bật ảnh
            $table->integer('order')->default(0); // Dùng để sắp xếp ảnh
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_images');
    }
};
