<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDonViIdToVbdEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vbd_email', function (Blueprint $table) {
            $table->integer('don_vi_id')->nullable();
            $table->integer('user_id')->nullable()->comment('id nguoi tao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vbd_email', function (Blueprint $table) {
            $table->dropColumn('don_vi_id');
            $table->dropColumn('user_id');
        });
    }
}
