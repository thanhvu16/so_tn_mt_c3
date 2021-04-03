<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDaThamMuuToDhvbdDonViChuTri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->tinyInteger('da_tham_muu')->after('vao_so_van_ban')->nullable()->comment('null chưa tham mưu, 1 đã tham mưu');
        });

        Schema::table('dhvbd_don_vi_phoi_hop', function (Blueprint $table) {
            $table->tinyInteger('da_tham_muu')->after('vao_so_van_ban')->nullable()->comment('null chưa tham mưu, 1 đã tham mưu');
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
            $table->dropColumn('da_tham_muu');
        });

        Schema::table('dhvbd_don_vi_phoi_hop', function (Blueprint $table) {
            $table->dropColumn('da_tham_muu');
        });
    }
}
