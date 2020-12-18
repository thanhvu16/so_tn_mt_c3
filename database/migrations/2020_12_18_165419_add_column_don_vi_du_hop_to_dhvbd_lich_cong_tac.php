<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDonViDuHopToDhvbdLichCongTac extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_lich_cong_tac', function (Blueprint $table) {
            $table->tinyInteger('don_vi_du_hop')->nullable()->comment('1 => la don vi du hop');
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
            $table->dropColumn('don_vi_du_hop');
        });
    }
}
