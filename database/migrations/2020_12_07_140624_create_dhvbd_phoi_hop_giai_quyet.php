<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdPhoiHopGiaiQuyet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_phoi_hop_giai_quyet', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->string('noi_dung')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:giai quyet cua don vi phoi hop, 2:giai quyet cua chuyen vien phoi hop');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dhvbd_phoi_hop_giai_quyet');
    }
}
