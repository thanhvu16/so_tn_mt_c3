<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNgayNghi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngay_nghi', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ngay_nghi')->nullable();
            $table->string('mo_ta')->nullable();
            $table->date('ngay_nghi')->nullable();
            $table->integer('thu_tu')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
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
        Schema::dropIfExists('ngay_nghi');
    }
}
