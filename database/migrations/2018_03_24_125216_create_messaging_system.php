<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagingSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_one')->unsigned();
            $table->integer('user_two')->unsigned();
            $table->timestamps();
  
            $table->foreign('user_one')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_two')->references('id')->on('users')->onDelete('cascade');
        });
  
  
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_user')->unsigned();
            $table->integer('to_user')->unsigned();
            $table->integer('conversation_id')->unsigned();
            $table->text('body');
            $table->tinyInteger('status')->default(0);
  
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('from_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('messages');
    }
}
