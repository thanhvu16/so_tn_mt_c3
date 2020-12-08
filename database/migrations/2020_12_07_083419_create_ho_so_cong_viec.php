<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoSoCongViec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hscv_list_ho_so', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ho_so')->nullable();
            $table->string('mo_ta')->nullable();
            $table->integer('nguoi_tao')->nullable();
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
        Schema::dropIfExists('hscv_list_ho_so');
    }
}
