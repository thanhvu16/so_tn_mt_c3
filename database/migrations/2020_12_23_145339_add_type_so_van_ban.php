<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeSoVanBan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('so_van_ban', function (Blueprint $table) {
            $table->tinyInteger('type')->default(1)->comment('1 sổ riêng văn bản đến 2 sổ riêng văn bản đi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('so_van_ban', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
