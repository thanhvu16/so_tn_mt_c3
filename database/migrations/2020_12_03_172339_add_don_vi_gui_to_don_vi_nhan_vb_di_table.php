<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDonViGuiToDonViNhanVbDiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('don_vi_nhan_van_ban_di', function (Blueprint $table) {
            $table->integer('don_vi_gui')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('don_vi_nhan_van_ban_di', function (Blueprint $table) {
            //
        });
    }
}
