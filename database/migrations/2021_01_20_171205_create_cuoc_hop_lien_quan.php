<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuocHopLienQuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qlch_cuoc_hop_lien_quan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_lich_hop')->nullable();
            $table->integer('id_cuoc_hop_lien_quan')->nullable();
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
        Schema::dropIfExists('qlch_cuoc_hop_lien_quan');
    }
}
