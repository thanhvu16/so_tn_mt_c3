<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiaiQuyetCongViecDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giai_quyet_cong_viec_don_vi', function (Blueprint $table) {
            $table->id();
            $table->integer('chuyen_nhan_cong_viec_don_vi_id')->nullable();
            $table->integer('cong_viec_don_vi_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->string('noi_dung_nhan_xet')->nullable();
            $table->integer('lanh_dao_duyet_id')->comment('id lanh dao don vi duyet')->nullable();
            $table->tinyInteger('status')->comment('null => cho duyet, 1 => da duyet, 2 => tra lai')->nullable();
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
        Schema::dropIfExists('giai_quyet_cong_viec_don_vi');
    }
}
