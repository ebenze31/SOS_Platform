<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('line')->nullable();
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('my_logs');
    }
}
