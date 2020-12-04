<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonViNhanVanBanDi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('don_vi_nhan_van_ban_di', function (Blueprint $table) {
            $table->id();
            $table->integer('don_vi_id_nhan')->nullable();
            $table->integer('van_ban_di_id')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->comment('1: chưa vào sổ,2 đã vào sổ');
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
        Schema::dropIfExists('don_vi_nhan_van_ban_di');
    }
}
