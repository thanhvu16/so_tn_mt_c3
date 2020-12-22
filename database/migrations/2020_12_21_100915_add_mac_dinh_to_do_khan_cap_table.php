<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMacDinhToDoKhanCapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('do_khan_cap', function (Blueprint $table) {
            $table->tinyInteger('mac_dinh')->default(1)->comment('1 không 2 select');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('do_khan_cap', function (Blueprint $table) {
            $table->dropColumn('mac_dinh');
        });
    }
}
