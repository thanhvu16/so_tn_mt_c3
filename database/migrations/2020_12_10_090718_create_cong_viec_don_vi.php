<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongViecDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong_viec_don_vi', function (Blueprint $table) {
            $table->id();
            $table->string('noi_dung_cuoc_hop')->nullable();
            $table->string('noi_dung_dau_viec')->nullable();
            $table->integer('lich_cong_tac_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('cong_viec_don_vi');
    }
}
