<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLuuVetTrinhTuChuyen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('luu_vet_trinh_tu_chuyen_cu', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id')->nullable();
            $table->integer('can_bo_chuyen_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('noi_dung')->nullable();
            $table->tinyInteger('don_vi_co_dieu_hanh')->nullable();
            $table->tinyInteger('vao_so_van_ban')->nullable();
            $table->tinyInteger('chuyen_tiep')->nullable();
            $table->tinyInteger('hoan_thanh')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('tra_lai')->nullable();
            $table->tinyInteger('da_chuyen_xuong_don_vi')->nullable();
            $table->date('han_xu_ly_cu')->nullable();
            $table->date('han_xu_ly_moi')->nullable();
            $table->integer('parent_don_vi_id')->nullable();
            $table->tinyInteger('da_tham_muu')->nullable();
            $table->tinyInteger('van_ban_quan_trong')->nullable();
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
        Schema::dropIfExists('luu_vet_trinh_tu_chuyen_cu');
    }
}
