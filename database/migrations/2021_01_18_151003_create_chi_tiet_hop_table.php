<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietHopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qlch_chi_tiet_hop', function (Blueprint $table) {
            $table->id();
            $table->integer('lich_hop_id')->nullable();
            $table->text('y_kien_chinh_thuc')->nullable();
            $table->text('ket_luan_cuoc_hop')->nullable();
            $table->text('ghi_chep_HDND')->nullable();
            $table->text('ghi_chep_quan_uy')->nullable();
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
        Schema::dropIfExists('qlch_chi_tiet_hop');
    }
}
