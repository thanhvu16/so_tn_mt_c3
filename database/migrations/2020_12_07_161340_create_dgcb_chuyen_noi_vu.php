<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgcbChuyenNoiVu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dgcb_chuyen_noi_vu', function (Blueprint $table) {
            $table->id();
            $table->integer('phong')->nullable();
            $table->integer('can_bo_gui')->nullable();
            $table->integer('can_bo_nhan')->nullable()->comment('sau này nếu chỉ định 1 chuyên viên nhận');
            $table->integer('thang')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
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
        Schema::dropIfExists('dgcb_chuyen_noi_vu');
    }
}
