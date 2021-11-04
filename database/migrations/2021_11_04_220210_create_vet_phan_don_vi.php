<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetPhanDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('luu_vet_phan_lai', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id')->nullable();
            $table->integer('phong_cu')->nullable();
            $table->integer('nguoi_phan_lai')->nullable();
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
        Schema::dropIfExists('luu_vet_phan_lai');
    }
}
