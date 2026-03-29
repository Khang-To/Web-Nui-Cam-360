<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Đảm bảo 'key' không bị trùng (vd: email, phone...)
            $table->longText('value')->nullable(); // Dùng longText để chứa thoải mái dữ liệu dài (nếu có)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_settings');
    }
};
