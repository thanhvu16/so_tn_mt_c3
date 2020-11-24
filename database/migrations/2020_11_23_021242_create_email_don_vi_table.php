<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailDonViTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vbd_email_don_vi', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('ten_don_vi')->nullable();
            $table->date('ngay_nhap')->nullable();
            $table->string('mail_group')->nullable();
            $table->string('mail_cha')->nullable();
            $table->tinyInteger('trang_thai')->nullable();
            $table->integer('sdt')->nullable();
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
        Schema::dropIfExists('vbd_email_don_vi');
    }
}
