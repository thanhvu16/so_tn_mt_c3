<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdXuLyVanBanDen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_xu_ly_van_ban_den', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('can_bo_chuyen_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->string('tom_tat')->nullable();
            $table->tinyInteger('status')->nullable()->comment('1 tra lai tham muu');
            $table->tinyInteger('tu_tham_muu')->nullable()->comment('1: tham mưu gửi');
            $table->date('han_xu_ly')->nullable()->comment('han ld nhap');
            $table->tinyInteger('lanh_dao_chi_dao')->nullable()->comment('1: can bo chi dao vb');
            $table->tinyInteger('quyen_gia_han')->nullable()->comment('1: co quyen gia han');
            $table->tinyInteger('hoan_thanh')->nullable()->comment('1 vb da hoan thanh');
            $table->integer('user_id')->comment('can bo nhap');
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
        Schema::dropIfExists('dhvbd_xu_ly_van_ban_den');
    }
}
