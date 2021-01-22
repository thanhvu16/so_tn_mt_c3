<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanhGiaTaiLieuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qlch_danh_gia_tai_lieu', function (Blueprint $table) {
            $table->id();
            $table->integer('id_phong')->nullable();
            $table->integer('id_lich_ct')->nullable();
            $table->string('nhan_xet')->nullable();
            $table->tinyInteger('danh_gia_chat_luong_chuan_bi_tai_lieu')->comment('1: đạt 2:không đạt')->nullable();
            $table->tinyInteger('trang_thai')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qlch_danh_gia_tai_lieu');
    }
}
