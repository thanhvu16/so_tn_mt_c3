<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhomDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhom_don_vi', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nhom_don_vi')->nullable();
            $table->string('mo_ta')->nullable();
            $table->integer('thu_tu')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
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
        Schema::dropIfExists('nhom_don_vi');
    }
}
