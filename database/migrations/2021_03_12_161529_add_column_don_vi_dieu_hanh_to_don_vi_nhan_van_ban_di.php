<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDonViDieuHanhToDonViNhanVanBanDi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('don_vi_nhan_van_ban_di', function (Blueprint $table) {
            $table->tinyInteger('dieu_hanh')->after('trang_thai')->default(0)->comment('1: don vi Có điều hành 0: don vi không có điều hành');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('don_vi_nhan_van_ban_di', function (Blueprint $table) {
            $table->dropColumn('dieu_hanh');
        });
    }
}
