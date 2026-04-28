<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutoAssignToAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->string('auto_assign')->nullable();
            $table->string('day_command')->nullable();
            $table->time('time_start_command')->nullable();
            $table->time('time_end_command')->nullable();
            $table->string('groupID')->nullable();
            $table->longText('officer_priority')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('areas', function (Blueprint $table) {
            //
        });
    }
}
