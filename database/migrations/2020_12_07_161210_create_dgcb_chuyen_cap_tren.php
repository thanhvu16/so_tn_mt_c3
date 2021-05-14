<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgcbChuyenCapTren extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dgcb_chuyen_cap_tren', function (Blueprint $table) {
            $table->id();
            $table->integer('can_bo_chuyen')->nullable();
            $table->integer('can_bo_nhan')->nullable();
            $table->float('diem')->nullable();
            $table->string('nhan_xet')->nullable();
            $table->integer('thang')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->integer('can_bo_goc')->nullable()->comment('Cán bộ đánh giá đầu tiên');
            $table->integer('danh_gia_chot')->nullable()->comment('Cán bộ đánh giá cuối cùng');
            $table->tinyInteger('da_danh_gia_xong')->default(1)->comment('1:Chưa đánh giá xong  2.Đã đánh giá xong');
            $table->integer('parent_id')->nullable()->comment('đánh giá cha');
            $table->integer('id_dau_tien')->nullable()->comment('lấy id của đánh giá đầu tiên');
            $table->integer('danh_gia_id')->nullable()->comment('chi tiết đánh giá của chuyên viên');
            $table->tinyInteger('cap_danh_gia')->default(1)->comment('1: chuyên viên 2 trưởng phòng 3:phó phòng');
            $table->tinyInteger('lanh_dao_da_danh_gia')->default(1)->comment('1: chưa đánh gia 2 đã đánh gia chờ xác nhận 3:đánh giá đã chốt');
            $table->tinyInteger('trang_thai')->default(1)->comment('1: chưa đánh giá  3:cấp phó đã đánh giá 4: cấp trưởng đã đánh giá');
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
        Schema::dropIfExists('dgcb_chuyen_cap_tren');
    }
}
