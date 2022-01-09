<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeDonViCapChiNhanhVanBanDen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->integer('van_ban_chi_nhanh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->dropColumn('van_ban_chi_nhanh');
        });
    }
}
