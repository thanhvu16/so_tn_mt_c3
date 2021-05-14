<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongViecDonViGiaHan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong_viec_don_vi_gia_han', function (Blueprint $table) {
            $table->id();
            $table->integer('chuyen_nhan_cong_viec_don_vi_id')->nullable();
            $table->integer('cong_viec_don_vi_id')->nullable();
            $table->integer('can_bo_chuyen_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->date('han_cu')->nullable();
            $table->date('thoi_han_de_xuat')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: cho duyet, 2: tra lai, 3 da duyet');
            $table->integer('don_vi_id')->nullable();
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
        Schema::dropIfExists('cong_viec_don_vi_gia_han');
    }
}
