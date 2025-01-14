<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image_url'); // Đường dẫn hình ảnh banner
            $table->string('link')->nullable(); // Link dẫn khi người dùng click vào banner
            $table->boolean('is_active')->default(true); // Trạng thái banner có hoạt động hay không
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('banners');
    }
    
};
