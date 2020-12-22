<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnToDhvbdDonViPhoiHopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_don_vi_phoi_hop', function (Blueprint $table) {
            $table->tinyInteger('don_vi_co_dieu_hanh')->nullable()->after('noi_dung')->comment('1 : co dieu hanh, null k dieu hanh');
            $table->tinyInteger('vao_so_van_ban')->nullable()->after('don_vi_co_dieu_hanh')->comment('1 => da vao so van ban don vi co dieu hanh');
            $table->tinyInteger('type')->nullable()->after('vao_so_van_ban')->comment('null: chuyen tu huyen xuong don vi, 1: nhap truc tiep tu van thu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dhvbd_don_vi_phoi_hop', function (Blueprint $table) {
            $table->dropColumn('don_vi_co_dieu_hanh');
            $table->dropColumn('vao_so_van_ban');
            $table->dropColumn('type');
        });
    }
}
