<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maxes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('time10')->default('5')->nullable();
            $table->integer('time11')->default('5')->nullable();
            $table->integer('time12')->default('5')->nullable();
            $table->integer('time13')->default('5')->nullable();
            $table->integer('time14')->default('5')->nullable();
            $table->integer('time15')->default('5')->nullable();
            $table->integer('time16')->default('5')->nullable();
            $table->integer('time17')->default('5')->nullable();
            $table->integer('time18')->default('5')->nullable();
            $table->integer('time19')->default('5')->nullable();
            $table->integer('time20')->default('5')->nullable();
            $table->integer('time21')->default('5')->nullable();
            $table->integer('time22')->default('5')->nullable();
            $table->integer('time23')->default('5')->nullable();
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
        Schema::dropIfExists('maxes');
    }
}
