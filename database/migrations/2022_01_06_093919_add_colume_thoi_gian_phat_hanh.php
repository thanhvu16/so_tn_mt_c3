<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeThoiGianPhatHanh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_di', function (Blueprint $table) {
            $table->dateTime('thoi_gian_gui')->nullable();
            $table->dateTime('thoi_gian_phat_hanh')->nullable();
            $table->integer('so_ban')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('van_ban_di', function (Blueprint $table) {
            $table->dropColumn('thoi_gian_gui');
            $table->dropColumn('thoi_gian_phat_hanh');
            $table->dropColumn('so_ban');
        });
    }
}
