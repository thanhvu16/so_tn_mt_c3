<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongViecDonViPhoiHopGiaiQuyet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong_viec_don_vi_phoi_hop_giai_quyet', function (Blueprint $table) {
            $table->id();
            $table->integer('cong_viec_don_vi_id')->nullable();
            $table->integer('chuyen_nhan_cong_viec_don_vi_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:giai quyet cua don vi phoi hop, 2:giai quyet cua chuyen vien phoi hop');
            $table->integer('don_vi_id')->nullable();
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
        Schema::dropIfExists('cong_viec_don_vi_phoi_hop_giai_quyet');
    }
}
