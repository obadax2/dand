<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoryIdToMapsTable extends Migration
{
    public function up()
    {
        Schema::table('maps', function (Blueprint $table) {
            // Add nullable story_id column first (in case you have existing rows)
            $table->foreignId('story_id')->nullable()->after('id')->constrained('stories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->dropForeign(['story_id']);
            $table->dropColumn('story_id');
        });
    }
}
