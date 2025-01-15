<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('review_replies', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('content')->nullable();
        });
    }

    public function down()
    {
        Schema::table('review_replies', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }

};
