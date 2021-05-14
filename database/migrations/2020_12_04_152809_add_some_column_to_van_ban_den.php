<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnToVanBanDen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->tinyInteger('hoan_thanh_dung_han')->default(1)->comment('1 => dung han, 2 => qua han');
            $table->dateTime('ngay_hoan_thanh')->nullable();
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
            $table->dropColumn('hoan_thanh_dung_han');
            $table->dropColumn('ngay_hoan_thanh');
        });
    }
}
