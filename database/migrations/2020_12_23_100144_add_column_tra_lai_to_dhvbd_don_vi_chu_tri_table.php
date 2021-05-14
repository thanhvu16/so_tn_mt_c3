<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTraLaiToDhvbdDonViChuTriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->tinyInteger('tra_lai')->nullable()->after('hoan_thanh')->comment('1 => truong phong tra lai cho van thu');
            $table->tinyInteger('da_chuyen_xuong_don_vi')->nullable()->after('noi_dung')->comment('null => chưa chuyển xuống đơn vị, 1 => đã chuyển xuống đơn vị');
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
            $table->dropColumn('tra_lai');
            $table->dropColumn('da_chuyen_xuong_don_vi');
        });
    }
}
