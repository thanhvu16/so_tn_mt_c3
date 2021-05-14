<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanhGiaGpYTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qlch_danh_gia_gop_y', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('id_lich_hop')->nullable();
            $table->string('nhan_xet')->nullable();
            $table->text('trao_doi_thao_luan')->nullable();
            $table->tinyInteger('danh_gia_chat_luong_gop_y_y_kien')->comment('1: đạt 2:không đạt')->nullable();
            $table->tinyInteger('trang_thai')->nullable();
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
        Schema::dropIfExists('qlch_danh_gia_gop_y');
    }
}
