<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnActiveToDhvbdDonViPhoiHop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_don_vi_phoi_hop', function (Blueprint $table) {
            $table->tinyInteger('active')->nullable()->after('chuyen_tiep')->comment('1: nhan vb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dhvbd_don_vi_phoi_hop', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
