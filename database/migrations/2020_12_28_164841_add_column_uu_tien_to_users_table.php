<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUuTienToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('uu_tien')->nullable();
        });

        Schema::table('dhvbd_log_xu_ly_van_ban_den', function (Blueprint $table) {
            $table->text('noi_dung')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uu_tien');
        });

        Schema::table('dhvbd_log_xu_ly_van_ban_den', function (Blueprint $table) {
            $table->string('noi_dung')->nullable()->change();
        });
    }
}
