<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStoriesTableAddFields extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->string('title')->nullable()->after('user_id');
            $table->text('content')->nullable()->after('title');
            $table->string('genre')->nullable()->after('content');
            $table->string('status')->default('draft')->after('genre');
            // Uncomment if you want to explicitly add timestamps:
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'title',
                'content',
                'genre',
                'status',
            ]);
        });
    }
}