<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTruongPhongDaKy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_di', function (Blueprint $table) {
            $table->tinyInteger('truong_phong_ky')->default(1);
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
            $table->dropColumn('truong_phong_ky');
        });
    }
}
