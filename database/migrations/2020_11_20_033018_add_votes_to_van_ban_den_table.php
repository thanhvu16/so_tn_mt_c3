<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVotesToVanBanDenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->integer('nguoi_tao')->nullable();
            $table->integer('don_vi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            //
        });
    }
}
