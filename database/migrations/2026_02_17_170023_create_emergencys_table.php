<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergencys', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name_reporter')->nullable();
            $table->string('type_reporter')->nullable();
            $table->string('phone_reporter')->nullable();
            $table->string('emergency_type')->nullable();
            $table->string('emergency_detail')->nullable();
            $table->string('emergency_lat')->nullable();
            $table->string('emergency_lng')->nullable();
            $table->string('emergency_location')->nullable();
            $table->string('emergency_photo')->nullable();
            $table->string('score_impression')->nullable();
            $table->string('score_period')->nullable();
            $table->string('score_total')->nullable();
            $table->string('comment_help')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('emergencys');
    }
}
