<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // User name// Unique username column
          
            $table->string('username')->unique();
            $table->string('email')->unique(); // Unique email column
            $table->timestamp('email_verified_at')->nullable(); // For email verification
            $table->string('password'); // Password column
            $table->rememberToken(); // Token for remembering the user
            $table->enum('role', ['admin', 'user', 'hr']); // Role column
            $table->string('profile_picture')->nullable(); // Profile picture column
            $table->text('bio')->nullable(); // Bio column
            $table->string('tokens')->nullable(); // Additional tokens column as needed
            $table->unsignedInteger('dob_year')->nullable(); // Year of birth
            $table->unsignedTinyInteger('dob_month')->nullable(); // Month of birth
            $table->unsignedTinyInteger('dob_day')->nullable(); // Day of birth
            $table->timestamps(); // Created at and updated at timestamps
        });

        // Create additional needed tables
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // You'll typically want this as a unique field
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
}