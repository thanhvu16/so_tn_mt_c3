<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileChinhToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_van_ban_di', function (Blueprint $table) {
            $table->tinyInteger('file_chinh_gui_di')->default(1)->comment('1 file bình thường 2 file đã ký số hoặc upload lên');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_van_ban_di', function (Blueprint $table) {
            //
        });
    }
}
