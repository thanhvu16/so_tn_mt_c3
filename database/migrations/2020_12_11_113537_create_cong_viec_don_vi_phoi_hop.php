<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongViecDonViPhoiHop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong_viec_don_vi_phoi_hop', function (Blueprint $table) {
            $table->id();
            $table->integer('cong_viec_don_vi_id')->nullable();
            $table->integer('can_bo_chuyen_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->tinyInteger('type')->nullable()->comment('null => chuyen vien phoi hop dv chu tri, 1 => can bo xem de biet');
            $table->tinyInteger('status')->nullable()->comment('1 đã giải quyết');
            $table->integer('chuyen_nhan_cong_viec_don_vi_id')->nullable();
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
        Schema::dropIfExists('cong_viec_don_vi_phoi_hop');
    }
}
