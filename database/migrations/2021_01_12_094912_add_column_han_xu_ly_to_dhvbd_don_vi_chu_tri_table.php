<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHanXuLyToDhvbdDonViChuTriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_don_vi_chu_tri', function (Blueprint $table) {
            $table->date('han_xu_ly_cu')->nullable()->after('type');
            $table->date('han_xu_ly_moi')->nullable()->after('han_xu_ly_cu');
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
           $table->dropColumn('han_xu_ly_cu');
           $table->dropColumn('han_xu_ly_moi');
        });
    }
}
