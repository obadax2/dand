<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollVotesTable extends Migration
{
    public function up()
    {
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('vote', ['yes', 'no']);
            $table->timestamps();

            $table->unique(['poll_id', 'user_id']); // One vote per user per poll
        });
    }

    public function down()
    {
        Schema::dropIfExists('poll_votes');
    }
}
