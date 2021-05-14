<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailNgoaiTp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_thongtin_donvi', function (Blueprint $table) {
            $table->id();
            $table->string('ma_dinh_danh')->nullable();
            $table->string('ten_don_vi')->nullable();
            $table->string('email')->nullable();
            $table->string('dia_chi')->nullable();
            $table->string('sdt')->nullable();
            $table->string('web')->nullable();
            $table->tinyInteger('accepted')->default(1);
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
        Schema::dropIfExists('tbl_thongtin_donvi');
    }
}
