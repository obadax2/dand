<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalCharacterIdToCharactersTable extends Migration
{
    public function up()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->unsignedBigInteger('original_character_id')->nullable()->after('id');

            // Optional: add foreign key constraint if you want
            // $table->foreign('original_character_id')->references('id')->on('characters')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('characters', function (Blueprint $table) {
            // Drop foreign key first if added
            // $table->dropForeign(['original_character_id']);

            $table->dropColumn('original_character_id');
        });
    }
}
