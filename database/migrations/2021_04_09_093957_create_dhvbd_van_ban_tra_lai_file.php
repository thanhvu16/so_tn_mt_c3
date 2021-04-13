<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdVanBanTraLaiFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_van_ban_tra_lai_file', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_tra_lai_id')->comment('id table dhvbd_van_ban_tra_lai');
            $table->string('ten_file')->nullable();
            $table->string('url_file')->nullable();
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
        Schema::dropIfExists('dhvbd_van_ban_tra_lai_file');
    }
}
