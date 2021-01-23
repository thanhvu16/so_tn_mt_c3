<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileTaiLieuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qlch_file_tai_lieu', function (Blueprint $table) {
            $table->id();
            $table->string('ten_file')->nullable();
            $table->string('duong_dan')->nullable();
            $table->string('duoi_file')->nullable();
            $table->integer('lich_hop_id')->nullable();
            $table->integer('nguoi_tao')->nullable();
            $table->tinyInteger('trang_thai')->comment('1: tài liệu tham khảo  2 tài liệu cuộc họp 3: file kết luận cuộc hop')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('qlch_file_tai_lieu');
    }
}
