<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('temporary_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->integer('dob_day');
            $table->integer('dob_month');
            $table->integer('dob_year');
            $table->string('email')->unique();
            $table->string('verification_code');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('temporary_users');
    }
};
