<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdLanhDaoXemDeBiet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_lanh_dao_xem_de_biet', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('lanh_dao_id')->nullable();
            $table->integer('don_vi_id')->nullable()->comment('null là lãnh đạo xem để biết, khác null là phó phòng xem để biết');
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
        Schema::dropIfExists('dhvbd_lanh_dao_xem_de_biet');
    }
}
