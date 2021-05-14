<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThanhPhanNgoaiToChTietCuocHop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qlch_chi_tiet_hop', function (Blueprint $table) {
            $table->text('thanh_phan_ben_ngoai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qlch_chi_tiet_hop', function (Blueprint $table) {
            $table->dropColumn('thanh_phan_ben_ngoai');
        });
    }
}
