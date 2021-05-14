<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPasswordToDonVi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('don_vi', function (Blueprint $table) {
            $table->string('password')->after('email')->nullable();
            $table->tinyInteger('status_email')->after('password')->nullable()
                ->comment('1: hoat dong, 2: khoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('don_vi', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('status_email');
        });
    }
}
