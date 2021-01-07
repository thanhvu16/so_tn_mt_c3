<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNhomDonViToChucVuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chuc_vu', function (Blueprint $table) {
           $table->string('nhom_don_vi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chuc_vu', function (Blueprint $table) {
            $table->dropColumn('nhom_don_vi');
        });
    }
}
