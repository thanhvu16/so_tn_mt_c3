<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLctThanhPhanDuHop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lct_thanh_phan_du_hop', function (Blueprint $table) {
            $table->id();
            $table->integer('lich_cong_tac_id')->comment('id cua dhvbd_lich_cong_tac');
            $table->integer('object_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->tinyInteger('type')->nullable()->comment('null la vb den, 1: la van ban di, 2 nhap lich truc tiep');
            $table->integer('lanh_dao_id')->nullable()->comment('lanh dao cuoc hop');
            $table->integer('user_id')->nullable()->comment('can_bo_du_hop');
            $table->text('noi_dung')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->comment('1:đi 2:bận');
            $table->tinyInteger('thanh_phan')->default(1)->comment('1:mời dự họp 2:lãnh đạo 3:tổng hợp');
            $table->tinyInteger('thanh_phan_moi')->default(1)->comment('1:k 2:có thành phần mời dự họp');
            $table->tinyInteger('chat_luong')->default(1)->comment('1:dat 2:kdat');
            $table->string('nhan_xet')->nullable();
            $table->tinyInteger('trang_thai_lich')->default(1)->comment('1:bt 2:da chuyen lich');
            $table->integer('nguoi_tao_id');
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
        Schema::dropIfExists('lct_thanh_phan_du_hop');
    }
}
