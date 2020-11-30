<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileVanBanDi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_van_ban_di', function (Blueprint $table) {
            $table->id();
            $table->string('ten_file')->nullable();
            $table->string('duong_dan')->nullable();
            $table->string('duoi_file')->nullable();
            $table->integer('van_ban_di_id')->nullable();
            $table->integer('nguoi_dung_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->nullable();
            $table->tinyInteger('loai_file')->nullable();
            $table->tinyInteger('trang_thai_gui')->nullable();
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
        Schema::dropIfExists('file_van_ban_di');
    }
}
