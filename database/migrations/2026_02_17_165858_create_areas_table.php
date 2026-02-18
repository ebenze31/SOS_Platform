<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name_area')->nullable();
            $table->string('type')->nullable();
            $table->longText('polygon')->nullable();
            $table->string('status')->nullable();
            $table->string('creator')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('areas');
    }
}
