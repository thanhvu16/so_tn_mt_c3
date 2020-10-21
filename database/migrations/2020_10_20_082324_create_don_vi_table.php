<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonViTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('don_vi', function (Blueprint $table) {
            $table->id();
            $table->string('ten_don_vi')->nullable();
            $table->string('ten_viet_tat')->nullable();
            $table->string('ma_hanh_chinh')->nullable();
            $table->string('dia_chi')->nullable();
            $table->integer('so_dien_thoai')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('dieu_hanh')->default(1)->comment('1: Có điều hành 0: không có điều hành');
            $table->softDeletes();
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
        Schema::dropIfExists('don_vi');
    }
}

