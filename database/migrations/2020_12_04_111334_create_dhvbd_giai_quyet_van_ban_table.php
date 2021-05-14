<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhvbdGiaiQuyetVanBanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhvbd_giai_quyet_van_ban', function (Blueprint $table) {
            $table->id();
            $table->integer('van_ban_den_id')->nullable();
            $table->integer('van_ban_du_thao_id')->nullable();
            $table->integer('van_ban_di_id')->nullable();
            $table->text('noi_dung')->nullable();
            $table->string('noi_dung_nhan_xet')->nullable();
            $table->integer('user_id')->comment('id nguoi tao giai quyet');
            $table->integer('can_bo_duyet_id')->comment('id can bo duyet van ban');
            $table->dateTime('ngay_duyet')->nullable()->comment('ngay duyet hoan thanh van ban');
            $table->integer('status')->nullable()->comment('1: da duyet, 2: tra lai, null: cho duyet');
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
        Schema::dropIfExists('dhvbd_giai_quyet_van_ban');
    }
}
