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
        Schema::create('used_coupons', function (Blueprint $table) {
            $table->increments('used_coupon_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('coupon_id');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('coupon_id')->references('coupon_id')->on('coupons')->onDelete('cascade');
            $table->unique(['user_id', 'coupon_id']); // Đảm bảo mỗi người dùng chỉ được sử dụng mã 1 lần
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_coupons');
    }
};
