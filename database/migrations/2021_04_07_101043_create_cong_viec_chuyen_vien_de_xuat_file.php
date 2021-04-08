<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongViecChuyenVienDeXuatFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong_viec_chuyen_vien_de_xuat_file', function (Blueprint $table) {
            $table->id();
            $table->string('ten_file')->nullable();
            $table->string('duong_dan')->nullable();
            $table->string('duoi_file')->nullable();
            $table->integer('cong_viec_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->nullable();
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
        Schema::dropIfExists('cong_viec_chuyen_vien_de_xuat_file');
    }
}
