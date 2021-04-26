<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVbdEmailTable extends Migration
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
            $table->text('mail_subject')->nullable();
            $table->text('mail_from')->nullable();
            $table->dateTime('mail_date')->nullable();
            $table->text('mail_attachment')->nullable();
            $table->text('mail_pdf')->nullable();
            $table->text('mail_doc')->nullable();
            $table->text('mail_xls')->nullable();
            $table->tinyInteger('mail_active')->nullable();
            $table->text('noigui')->nullable();
            $table->tinyInteger('mail_status')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->integer('user_id')->nullable();
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
