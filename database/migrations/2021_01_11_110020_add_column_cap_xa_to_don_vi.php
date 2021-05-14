<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCapXaToDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('don_vi', function (Blueprint $table) {
            $table->tinyInteger('cap_xa')->nullable()->comment('1 la cap xa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('don_vi', function (Blueprint $table) {
            $table->dropColumn('cap_xa');
        });
    }
}
