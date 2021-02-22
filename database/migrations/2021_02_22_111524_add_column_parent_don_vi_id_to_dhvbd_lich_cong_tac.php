<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnParentDonViIdToDhvbdLichCongTac extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_lich_cong_tac', function (Blueprint $table) {
            $table->integer('parent_don_vi_id')->after('don_vi_du_hop')->nullable()->comment('id cha cua don vi du hop');
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
            $table->dropColumn('parent_don_vi_id');
        });
    }
}
