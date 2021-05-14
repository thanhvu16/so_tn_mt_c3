<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTrongTp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_email_don_vi', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('ten_don_vi')->nullable();
            $table->date('ngay_nhap')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->tinyInteger('mail_group')->default(1);
            $table->integer('mail_cha')->nullable();
            $table->string('sdt')->nullable();
            $table->tinyInteger('accepted')->default(1);
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
        Schema::dropIfExists('tbl_email_don_vi');
    }
}
