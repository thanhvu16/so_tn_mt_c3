<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeChuTri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_lich_cong_tac', function (Blueprint $table) {
            $table->tinyInteger('chu_tri')->nullable();
            $table->tinyInteger('du_hop')->nullable();
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
            $table->dropColumn('chu_tri');
            $table->dropColumn('du_hop');
        });
    }
}
