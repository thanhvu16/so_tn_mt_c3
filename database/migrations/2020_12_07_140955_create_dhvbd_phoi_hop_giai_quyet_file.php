<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdPhoiHopGiaiQuyetFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_phoi_hop_giai_quyet_file', function (Blueprint $table) {
            $table->id();
            $table->integer('phoi_hop_giai_quyet_id');
            $table->string('ten_file');
            $table->string('url_file');
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
        Schema::dropIfExists('dhvbd_phoi_hop_giai_quyet_file');
    }
}
