<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnParentDonViIdToDhvbdPhoiHopGiaiQuyet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_phoi_hop_giai_quyet', function (Blueprint $table) {
            $table->integer('parent_don_vi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dhvbd_phoi_hop_giai_quyet', function (Blueprint $table) {
            $table->dropColumn('parent_don_vi_id');
        });
    }
}
