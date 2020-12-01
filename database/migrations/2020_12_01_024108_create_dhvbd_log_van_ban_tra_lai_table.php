<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdLogVanBanTraLaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_log_van_ban_tra_lai', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('can_bo_chuyen_id');
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
        Schema::dropIfExists('dhvbd_log_van_ban_tra_lai');
    }
}
