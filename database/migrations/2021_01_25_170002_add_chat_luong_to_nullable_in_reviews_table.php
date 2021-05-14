<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChatLuongToNullableInReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lct_thanh_phan_du_hop', function (Blueprint $table) {
            $table->tinyInteger('chat_luong')->nullable()->comment('1:dạt 2 không đạt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lct_thanh_phan_du_hop', function (Blueprint $table) {
            $table->dropColumn('chat_luong');
        });
    }
}
