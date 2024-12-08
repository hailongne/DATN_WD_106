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
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('staff_id');
            $table->unsignedInteger('user_id'); 
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('position');
            $table->enum('role', ['admin', 'manager', 'sales', 'warehouse']);
            $table->decimal('salary', 10, 2)->nullable();  // Mức lương (nullable nếu không phải tất cả nhân viên có lương cố định)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
