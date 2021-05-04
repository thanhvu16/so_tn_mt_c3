<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVanBanDiAddPhatHanhVanBanToVanBanDi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_di', function (Blueprint $table) {
            $table->tinyInteger('phat_hanh_van_ban')->default(1)->comment('1: cho phat hanh, 2: da phat hanh');
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
            $table->dropColumn('phat_hanh_van_ban');
        });
    }
}
