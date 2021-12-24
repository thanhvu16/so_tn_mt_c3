<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGhiNhanDaXem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ghi_nhan_da_xem', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->time('gio_chuyen')->nullable();
            $table->date('ngay_chuyen')->nullable();
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
        Schema::dropIfExists('ghi_nhan_da_xem');
    }
}
