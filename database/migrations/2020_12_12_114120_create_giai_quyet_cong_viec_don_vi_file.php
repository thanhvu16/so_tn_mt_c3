<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiaiQuyetCongViecDonViFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giai_quyet_cong_viec_don_vi_file', function (Blueprint $table) {
            $table->id();
            $table->integer('giai_quyet_cong_viec_don_vi_id')->nullable();
            $table->string('ten_file')->nullable();
            $table->string('url_file')->nullable();
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
        Schema::dropIfExists('giai_quyet_cong_viec_don_vi_file');
    }
}
