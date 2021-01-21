<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnParentIdToDhvbdLichCongTacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dhvbd_lich_cong_tac', function (Blueprint $table) {
            $table->integer('parent_id')->after('object_id')->nullable();
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
            $table->dropColumn('parent_id');
        });
    }
}
