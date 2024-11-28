<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaxesTable extends Migration
{
    // 店舗の時間による最大枠数の指定用DB
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
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

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
