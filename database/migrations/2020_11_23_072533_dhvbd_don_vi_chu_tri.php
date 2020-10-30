<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DhvbdDonViChuTri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('can_bo_chuyen_id');
            $table->integer('can_bo_nhan_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->tinyInteger('don_vi_co_dieu_hanh')->nullable()->comment('1 : co dieu hanh, null k dieu hanh');
            $table->tinyInteger('vao_so_van_ban')->nullable()->comment('1 => da vao so van ban don vi co dieu hanh');
            $table->tinyInteger('chuyen_tiep')->nullable();
            $table->tinyInteger('hoan_thanh')->nullable()->comment('1 vb da hoan thanh');
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
        Schema::dropIfExists('dhvbd_don_vi_chu_tri');
    }
}
