<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVanBanDiVanBanDensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('van_ban_di_van_ban_den', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_di_id')->comment('id tbl van_ban_di');
            $table->integer('van_ban_den_id')->comment('id tbl van_ban_den');
            $table->integer('user_id');
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
        Schema::dropIfExists('van_ban_di_van_ban_den');
    }
}
