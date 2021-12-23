<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumVersionLuuVetDonViCu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('luu_vet_trinh_tu_chuyen_cu', function (Blueprint $table) {
            $table->integer('version')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('luu_vet_trinh_tu_chuyen_cu', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
}
