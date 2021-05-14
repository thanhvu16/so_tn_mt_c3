<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVanBanDiChoDuyet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('van_ban_di_cho_duyet', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_di_id')->nullable();
            $table->integer('can_bo_chuyen_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->integer('id_du_thao')->nullable();
            $table->string('y_kien_gop_y')->nullable();
            $table->tinyInteger('tra_lai')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->comment('1:chờ duyêt 2: đã duyệt 10:thành văn bản đi chờ số 5: trả lại chuyển duyệt tiếp');
            $table->tinyInteger('cho_cap_so')->nullable()->comment('1 đã duyệt xong 2: bị xóa 3 đã cấp số');
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
        Schema::dropIfExists('van_ban_di_cho_duyet');
    }
}
