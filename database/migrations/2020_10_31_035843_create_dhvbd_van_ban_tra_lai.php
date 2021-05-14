<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdVanBanTraLai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_van_ban_tra_lai', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('can_bo_chuyen_id');
            $table->integer('can_bo_nhan_id')->nullable();
            $table->string('noi_dung')->nullable()->comment('noi dung tra lai');
            $table->tinyInteger('type')->nullable()->comment('1: cap lanh dao tra lai, 2: cap don vi tra lai');
            $table->tinyInteger('status')->nullable()->comment('1: da giai quyet');
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
        Schema::dropIfExists('dhvbd_van_ban_tra_lai');
    }
}
