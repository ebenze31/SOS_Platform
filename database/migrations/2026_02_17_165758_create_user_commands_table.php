<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_commands', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name_command')->nullable();
            $table->string('command_role')->nullable();
            $table->string('number')->nullable();
            $table->string('status')->nullable();
            $table->string('creator')->nullable();
            $table->string('user_id')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_commands');
    }
}
