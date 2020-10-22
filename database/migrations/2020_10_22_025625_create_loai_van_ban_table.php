<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoaiVanBanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_van_ban', function (Blueprint $table) {
            $table->id();
            $table->string('ten_loai_van_ban')->nullable();
            $table->string('ten_viet_tat')->nullable();
            $table->string('mo_ta')->nullable();
            $table->integer('loai_van_ban')->nullable();
            $table->integer('loai_don_vi')->nullable();
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
        Schema::dropIfExists('loai_van_ban');
    }
}
