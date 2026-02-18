<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencyOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('emergency_id')->nullable();
            $table->string('notify')->nullable();
            $table->string('command_by')->nullable();
            $table->string('operating_code')->nullable();
            $table->string('waiting_reply')->nullable();
            $table->string('officer_refuse')->nullable();
            $table->string('officer_no_respond')->nullable();
            $table->string('status')->nullable();
            $table->string('remark_status')->nullable();
            $table->string('area_id')->nullable();
            $table->string('user_officers_id')->nullable();
            $table->dateTime('time_create_sos')->nullable();
            $table->dateTime('time_command')->nullable();
            $table->dateTime('time_go_to_help')->nullable();
            $table->dateTime('time_to_the_scene')->nullable();
            $table->dateTime('time_sos_success')->nullable();
            $table->string('time_sum_sos')->nullable();
            $table->string('photo_by_officer')->nullable();
            $table->string('remark_photo_by_officer')->nullable();
            $table->string('photo_succeed')->nullable();
            $table->string('remark_by_helper')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('emergency_operations');
    }
}
