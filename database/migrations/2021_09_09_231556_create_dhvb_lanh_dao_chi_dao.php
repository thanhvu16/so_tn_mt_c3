<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbLanhDaoChiDao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvb_lanh_dao_chi_dao', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('lanh_dao_id')->nullable();
            $table->string('y_kien')->nullable();
            $table->tinyInteger('trang_thai')->nullable();
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
        Schema::dropIfExists('dhvb_lanh_dao_chi_dao');
    }
}
