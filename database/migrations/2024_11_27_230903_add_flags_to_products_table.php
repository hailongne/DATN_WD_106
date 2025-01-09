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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_hot')->default(false); // Đánh dấu Sản phẩm nổi bật
            $table->boolean('is_best_seller')->default(false);  // Mặc định là 'không phải bán chạy'

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_hot', 'is_best_seller']);
        });
    }
};
