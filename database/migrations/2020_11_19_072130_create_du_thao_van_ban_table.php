<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuThaoVanBanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtvb_du_thao_van_ban_di', function (Blueprint $table) {
            $table->id();
            $table->integer('loai_van_ban_id')->nullable();
            $table->integer('du_thao_cha')->nullable();
            $table->string('so_ky_hieu')->nullable();
            $table->string('y_kien')->nullable();
            $table->string('vb_trich_yeu')->nullable();
            $table->integer('nguoi_ky')->nullable();
            $table->string('chuc_vu')->nullable();
            $table->integer('so_trang')->nullable();
            $table->integer('nguoi_tao')->nullable();
            $table->date('ngay_thang')->nullable();
            $table->date('han_xu_ly')->nullable();
            $table->integer('lan_du_thao')->nullable();
            $table->integer('van_ban_den_don_vi_id')->nullable();
            $table->integer('du_thao_id')->nullable();
            $table->tinyInteger('stt')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('du_thao_van_ban_di');
    }
}
