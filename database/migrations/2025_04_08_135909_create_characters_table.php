<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('story_id')->constrained('stories')->onDelete('cascade'); // Story ID foreign key
            $table->string('name'); // Character name
            $table->text('description')->nullable(); // Character description, nullable
            $table->string('image_url')->nullable(); // URL for the character's image, nullable
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
        Schema::dropIfExists('characters');
    }
}