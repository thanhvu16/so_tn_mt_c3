<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeVanThuChuyenXuong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->tinyInteger('van_thu_nhan')->nullable();
            $table->tinyInteger('da_vao_so')->nullable();
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
            $table->dropColumn('van_thu_nhan');
            $table->dropColumn('da_vao_so');
        });
    }
}
