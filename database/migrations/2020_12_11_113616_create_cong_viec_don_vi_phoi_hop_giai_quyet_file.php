<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCongViecDonViPhoiHopGiaiQuyetFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cong_viec_don_vi_phoi_hop_giai_quyet_file', function (Blueprint $table) {
            $table->id();
            $table->integer('cong_viec_don_vi_phoi_hop_giai_quyet_id')->nullable();
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
        Schema::dropIfExists('cong_viec_don_vi_phoi_hop_giai_quyet_file');
    }
}
