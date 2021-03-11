<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhongPhatHanhToDtvbDuThaoVanBanDi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtvb_du_thao_van_ban_di', function (Blueprint $table) {
            $table->integer('phong_phat_hanh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtvb_du_thao_van_ban_di', function (Blueprint $table) {
            $table->dropColumn('phong_phat_hanh');
        });
    }
}
