<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoaiVanBanSkhToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loai_van_ban', function (Blueprint $table) {
            $table->tinyInteger('nam_truoc_skh')->default(1)->comment('1:có , 2 là không');
            $table->tinyInteger('ma_van_ban')->default(1)->comment('1:có , 2 là không');
            $table->tinyInteger('ma_phong_ban')->default(1)->comment('1:có , 2 là không');
            $table->tinyInteger('ma_don_vi')->default(1)->comment('1:có , 2 là không');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loai_van_ban', function (Blueprint $table) {
            $table->dropColumn('nam_truoc_skh');
            $table->dropColumn('ma_van_ban');
            $table->dropColumn('ma_phong_ban');
            $table->dropColumn('ma_don_vi');
        });
    }
}
