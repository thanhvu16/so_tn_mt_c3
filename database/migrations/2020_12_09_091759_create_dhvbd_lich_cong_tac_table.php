<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdLichCongTacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_lich_cong_tac', function (Blueprint $table) {
            $table->id();
            $table->integer('object_id')->nullable()->comment('id cua vb di hoac id cua vb den');
            $table->tinyInteger('type')->nullable()->comment('null la vb den, 1: la van ban di, 2 nhap lich truc tiep');
            $table->integer('lanh_dao_id');
            $table->date('ngay')->nullable();
            $table->time('gio')->nullable();
            $table->string('tuan')->nullable();
            $table->text('noi_dung')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->tinyInteger('buoi')->default(1)->comment('1: buoi sang, 2: buoi chieu');
            $table->string('dia_diem')->nullable();
            $table->tinyInteger('trang_thai_lich')->default(1)->comment('1: lịch chính thức, 2: lịch hoãn, 3: lịch điều chỉnh, 4: lịch phát sinh');
            $table->string('ghi chu')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('dhvbd_lich_cong_tac');
    }
}
