<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_di', function (Blueprint $table) {
            $table->tinyInteger('type')->comment('1 văn bản của huyện 2 văn bản của đơn vị')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('van_ban_di', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
