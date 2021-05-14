<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNguoiThamDuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qlch_nguoi_tham_du', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('van_ban_id')->nullable();
            $table->integer('lich_hop_id')->nullable();
            $table->text('trao_doi_thao_luan')->nullable();
            $table->text('y_kien_chinh_thuc')->nullable();
            $table->tinyInteger('trang_thai')->nullable()->comment('1 được up tài liệu');
            $table->tinyInteger('them_tay')->nullable()->comment('người tham dự được thêm tay');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qlch_nguoi_tham_du');
    }
}
