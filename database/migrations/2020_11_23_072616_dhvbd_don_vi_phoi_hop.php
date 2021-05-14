<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DhvbdDonViPhoiHop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_don_vi_phoi_hop', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id');
            $table->integer('can_bo_chuyen_id');
            $table->integer('can_bo_nhan_id')->nullable();
            $table->integer('don_vi_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->tinyInteger('chuyen_tiep')->nullable();
            $table->tinyInteger('hoan_thanh')->nullable()->comment('1 vb da hoan thanh');
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
        //
    }
}
