<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVbdFileVanBanDenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vbd_file_van_ban_den', function (Blueprint $table) {
            $table->id();
            $table->string('ten_file')->nullable();
            $table->string('duong_dan')->nullable();
            $table->string('duoi_file')->nullable();
            $table->integer('vb_den_id')->nullable();
            $table->integer('nguoi_dung_id')->nullable();
            $table->integer('don_vi_id')->nullable();
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
        Schema::dropIfExists('vbd_file_van_ban_den');
    }
}
