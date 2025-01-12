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
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('comment')->nullable()->change(); // Cho phép giá trị null cho cột 'comment'
        });
    }
    
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('comment')->nullable(false)->change(); // Quay lại trạng thái ban đầu (nếu cần)
        });
    }
};
