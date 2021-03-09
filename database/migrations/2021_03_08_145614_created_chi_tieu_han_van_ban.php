<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedChiTieuHanVanBan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('han_van_ban', function (Blueprint $table) {
            $table->id();
            $table->string('ten_tieu_chuan')->nullable();
            $table->integer('so_ngay')->nullable();
            $table->string('mo_ta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('han_van_ban');
    }
}
