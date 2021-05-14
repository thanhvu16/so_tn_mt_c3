<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoSoDetailCongViec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hscv_detail_ho_so', function (Blueprint $table) {
            $table->id();
            $table->integer('id_van_ban')->nullable();
            $table->integer('id_ho_so')->nullable();
            $table->tinyInteger('loai_van_ban')->nullable();
            $table->tinyInteger('trang_thai')->nullable();
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
        Schema::dropIfExists('hscv_detail_ho_so');
    }
}
