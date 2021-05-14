<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgcbLanhDaoDuyet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dgcb_lanh_dao_duyet', function (Blueprint $table) {
            $table->id();
            $table->integer('danh_gia_id_cu')->nullable();
            $table->integer('phong')->nullable();
            $table->string('ca_nhan')->nullable();
            $table->integer('diem_ca_nhan')->nullable();
            $table->string('nhan_xet_pho_phong')->nullable();
            $table->integer('diem_pho_phong_cham')->nullable();
            $table->string('nhan_xet_truong_phong')->nullable();
            $table->integer('diem_truong_phong_cham')->nullable();
            $table->integer('thang')->nullable();
            $table->string('nhan_xet_tranh_vp')->nullable();
            $table->string('xep_loai')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->comment('1: giup viec đánh giá 2: lanh đạo đánh giá');
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
        Schema::dropIfExists('dgcb_lanh_dao_duyet');
    }
}
