<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYKienDuThaoPhongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtvb_y_kien_du_thao_phong_khac', function (Blueprint $table) {
            $table->id();
            $table->integer('can_bo_id')->nullable();
            $table->integer('du_thao_vb_id')->nullable();
            $table->string('y_kien')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
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
        Schema::dropIfExists('y_kien_du_thao_phong');
    }
}
