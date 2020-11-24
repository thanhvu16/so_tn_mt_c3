<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vbd_email', function (Blueprint $table) {
            $table->id();
            $table->string('mail_subject')->nullable();
            $table->string('mail_from')->nullable();
            $table->date('mail_date')->nullable();
            $table->string('mail_attachment')->nullable();
            $table->string('mail_pdf')->nullable();
            $table->string('mail_doc')->nullable();
            $table->string('mail_xls')->nullable();
            $table->string('mail_active')->nullable();
            $table->string('noigui')->nullable();
            $table->tinyInteger('mail_status')->nullable();
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
        Schema::dropIfExists('vbd_email');
    }
}
