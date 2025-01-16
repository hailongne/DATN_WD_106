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
        Schema::table('coupon_user', function (Blueprint $table) {
            // Thêm cột has_used với kiểu boolean và mặc định là false
            $table->boolean('has_used')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_user', function (Blueprint $table) {
            // Xóa cột has_used nếu rollback migration
            $table->dropColumn('has_used');
        });
    }
};
