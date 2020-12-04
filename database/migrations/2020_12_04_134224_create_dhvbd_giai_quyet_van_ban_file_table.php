<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdGiaiQuyetVanBanFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_giai_quyet_van_ban_file', function (Blueprint $table) {
            $table->id();
            $table->integer('giai_quyet_van_ban_id');
            $table->string('ten_file')->nullable();
            $table->string('url_file')->nullable();
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
        Schema::dropIfExists('dhvbd_giai_quyet_van_ban_file');
    }
}
