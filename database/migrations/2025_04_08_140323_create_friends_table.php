<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('user1_id')->constrained('users')->onDelete('cascade'); // First user ID foreign key
            $table->foreignId('user2_id')->constrained('users')->onDelete('cascade'); // Second user ID foreign key
            $table->enum('status', ['pending', 'accepted', 'rejected', 'blocked']); // Friendship status
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
}