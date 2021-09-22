<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileEmailCongVu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_pdf_hom_thu_cong', function (Blueprint $table) {
            $table->id();
            $table->integer('email_id')->nullable();
            $table->string('duong_dan')->nullable();
            $table->integer('duoi_file')->nullable();
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
        Schema::dropIfExists('file_pdf_hom_thu_cong');
    }
}
