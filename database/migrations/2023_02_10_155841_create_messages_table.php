<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('message');
            $table->boolean('is_seen')->default(0);
            $table->integer('deleted_from_sender')->default(0);
            $table->integer('deleted_from_receiver')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('conversation_id')->constrained('conversations');

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
        Schema::dropIfExists('messages');
    }
};
