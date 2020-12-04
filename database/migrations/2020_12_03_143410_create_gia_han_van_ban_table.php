<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiaHanVanBanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_gia_han_van_ban', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('can_bo_chuyen_id')->nullable();
            $table->integer('can_bo_nhan_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->text('noi_dung')->nullable();
            $table->date('thoi_han_de_xuat')->nullable();
            $table->date('thoi_han_cu')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: cho duyet, 2: tra lai, 3 da duyet');
            $table->tinyInteger('lanh_dao_duyet')->default(1)->comment('1: cho duyet, 2: tra lai, 3 da duyet');
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
        Schema::dropIfExists('dhvbd_gia_han_van_ban');
    }
}
