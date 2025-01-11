<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attribute_products', function (Blueprint $table) {
            $table->increments('attribute_product_id');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->unsignedInteger('color_id');
            $table->foreign('color_id')->references('color_id')->on('colors')->onDelete('cascade');
            $table->unsignedInteger('size_id');
            $table->foreign('size_id')->references('size_id')->on('sizes')->onDelete('cascade');
            $table->integer('in_stock')->default(6);
            $table->double('price', 16, 2)->default(value: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_products');
    }
};
