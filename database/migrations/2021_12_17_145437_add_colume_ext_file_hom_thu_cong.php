<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeExtFileHomThuCong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_pdf_hom_thu_cong', function (Blueprint $table) {
            $table->string('duoi_file_pdf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_pdf_hom_thu_cong', function (Blueprint $table) {
            $table->dropColumn('duoi_file_pdf');
        });
    }
}
