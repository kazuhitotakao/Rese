<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('area');
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->string('overview')->nullable();
            $table->string('image')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('interval')->default('15');
            $table->integer('amount')->default('3000')->nullable();
            $table->integer('average_rating')->nullable();
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
        Schema::dropIfExists('shops');
    }
}
