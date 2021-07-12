<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeVanBanQuanTrong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->tinyInteger('van_ban_quan_trong')->nullable()->comment('1 là văn bản quan trọng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->dropColumn('van_ban_quan_trong');
        });
    }
}
