<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhomDonViChucVuToChucVuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhom_don_vi_chuc_vu', function (Blueprint $table) {
            $table->id();
            $table->integer('id_chuc_vu')->nullable();
            $table->integer('id_nhom_don_vi')->nullable();
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
        Schema::dropIfExists('nhom_don_vi_chuc_vu');
    }
}
