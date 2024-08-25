<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('time')->nullable();
            $table->foreignId('number_id')->constrained()->cascadeOnDelete();
            $table->integer('review')->nullable();
            $table->string('comment')->nullable();
            $table->timestamp('comment_at')->nullable();
            $table->boolean('review_mail_sent')->nullable();
            $table->string('check_in')->nullable();
            $table->timestamp('check_in_at')->nullable();
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
        Schema::dropIfExists('reservations');
    }
}
