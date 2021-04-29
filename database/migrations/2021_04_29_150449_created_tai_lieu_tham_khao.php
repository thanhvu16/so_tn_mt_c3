<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedTaiLieuThamKhao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tai_lieu_tham_khao', function (Blueprint $table) {
            $table->id();
            $table->string('ten_file')->nullable();
            $table->string('duong_dan')->nullable();
            $table->string('duoi_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tai_lieu_tham_khao');
    }
}
