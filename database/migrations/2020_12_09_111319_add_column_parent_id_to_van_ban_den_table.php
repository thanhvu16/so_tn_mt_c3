<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnParentIdToVanBanDenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->integer('parent_id')->after('id')->nullable();
        });

        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->tinyInteger('type')->after('vao_so_van_ban')->nullable()->comment('null: chuyen tu huyen xuong don vi, 1: nhap truc tiep tu van thu');
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
            $table->dropColumn('parent_id');
        });

        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->dropColumn('vao_so_van_ban');
        });
    }
}
