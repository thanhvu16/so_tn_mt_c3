<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChuyenNhanCongViecDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chuyen_nhan_cong_viec_don_vi', function (Blueprint $table) {
            $table->id();
            $table->integer('cong_viec_don_vi_id')->nullable();
            $table->integer('can_bo_chuyen_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('don_vi_id')->nullable()->comment('don vi thuc hien');
            $table->string('noi_dung')->nullable();
            $table->string('noi_dung_chuyen')->nullable();
            $table->date('han_xu_ly')->nullable();
            $table->tinyInteger('type')->nullable()->comment('1: don vi phoi hop, null: don vi thuc hien');
            $table->tinyInteger('hoan_thanh')->nullable()->comment('1 cv da hoan thanh');
            $table->tinyInteger('chuyen_tiep')->nullable()->comment('1: phối hợp chuyển tiếp xuống cấp dưới, 2: giải quyết');
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
        Schema::dropIfExists('chuyen_nhan_cong_viec_don_vi');
    }
}
