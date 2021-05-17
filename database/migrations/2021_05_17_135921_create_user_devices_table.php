<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->text('token');
            $table->tinyInteger('type')->default(1)->comment('1 ~ ios general token, 2 ~ android');
            $table->integer('user_id');
            $table->string('os_version', 20)->nullable();
            $table->string('app_version', 20)->nullable();
            $table->string('os_name', 20)->nullable();
            $table->string('api_version', 10)->nullable()->default('1');
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
        Schema::dropIfExists('user_devices');
    }
}
