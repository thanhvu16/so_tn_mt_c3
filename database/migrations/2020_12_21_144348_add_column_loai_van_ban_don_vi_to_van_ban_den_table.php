<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLoaiVanBanDonViToVanBanDenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->tinyInteger('loai_van_ban_don_vi')->nullable()->comment('null=>van ban don vi chu tri, 1=>vb don vi phoi hop');
        });

        Schema::table('dhvbd_phoi_hop_giai_quyet', function (Blueprint $table) {
            $table->integer('don_vi_id')->nullable()->comment('don vi giai quyet');
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
            $table->dropColumn('loai_van_ban_don_vi');
        });

        Schema::table('dhvbd_phoi_hop_giai_quyet', function (Blueprint $table) {
            $table->dropColumn('don_vi_id');
        });
    }
}
