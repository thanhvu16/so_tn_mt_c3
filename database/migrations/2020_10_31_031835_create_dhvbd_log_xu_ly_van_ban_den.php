<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdLogXuLyVanBanDen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_log_xu_ly_van_ban_den', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('can_bo_chuyen_id');
            $table->integer('can_bo_nhan_id')->nullable();
            $table->string('noi_dung')->nullable()->comment('noi dung chi dao');
            $table->integer('don_vi_id')->nullable()->comment('don vi thuc hien');
            $table->json('don_vi_phoi_hop_id')->nullable()->comment('don vi phoi hop');
            $table->tinyInteger('status')->nullable()->comment('1: can bo nhan dau tien');
            $table->tinyInteger('tu_tham_muu')->nullable()->comment('1: can bo nhan vb dau tien');
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
        Schema::dropIfExists('dhvbd_log_xu_ly_van_ban_den');
    }
}
