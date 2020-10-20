<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('ho_ten')->nullable();
            $table->string('gioi_tinh')->nullable()->comment('1: nam, 2: nu');
            $table->date('ngay_sinh')->nullable();
            $table->string('ma_nhan_su')->nullable();
            $table->string('anh_dai_dien')->nullable();
            $table->string('cmnd')->nullable();
            $table->string('trinh_do')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->string('so_dien_thoai_ky_sim')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->integer('chuc_vu_id')->nullable();
            $table->integer('chu_ky_chinh')->nullable();
            $table->integer('chu_ky_nhay')->nullable();
            $table->tinyInteger('trang_thai')->default(1)->comment('1=> hoat dong', '2 => khoa');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
