<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongViecChuyenVienDeXuat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong_viec_chuyen_vien_de_xuat', function (Blueprint $table) {
            $table->id();
            $table->text('noi_dung')->nullable();
            $table->integer('nguoi_gui')->nullable();
            $table->integer('truong_phong')->nullable();
            $table->date('han_xu_ly')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->comment('1: gửi trưởng phòng 2:đã duyêt');
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
        Schema::dropIfExists('cong_viec_chuyen_vien_de_xuat');
    }
}
