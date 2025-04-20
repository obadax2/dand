<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailVerificationCodeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adding the 'email_verification_code' column
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_verification_code')->nullable(); // or you can set a default value
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Dropping the 'email_verification_code' column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verification_code');
        });
    }
}