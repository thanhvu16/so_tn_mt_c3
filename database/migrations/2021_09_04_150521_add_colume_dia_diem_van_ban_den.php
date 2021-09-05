<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeDiaDiemVanBanDen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->string('dia_diem_to_cao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('van_ban_den', function (Blueprint $table) {
            $table->dropColumn('dia_diem_to_cao');
        });
    }
}
