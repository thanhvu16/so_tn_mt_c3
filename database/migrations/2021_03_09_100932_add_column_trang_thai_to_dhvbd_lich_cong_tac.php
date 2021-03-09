<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTrangThaiToDhvbdLichCongTac extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_lich_cong_tac', function (Blueprint $table) {
            $table->tinyInteger('trang_thai')->nullable()->comment('null chờ duyệt: 1 lãnh đạo   đã duyệt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dhvbd_lich_cong_tac', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
}
