<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoVanBanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_van_ban', function (Blueprint $table) {
            $table->id();
            $table->string('ten_so_van_ban')->nullable();
            $table->string('ten_viet_tat')->nullable();
            $table->string('mo_ta')->nullable();
            $table->integer('loai_so')->nullable();
            $table->integer('so_don_vi')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('so_van_ban');
    }
}
