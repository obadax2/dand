<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // We might need this if using older Laravel versions or specific database types, but for simple default change, Schema is usually enough.

class AlterUsersRoleDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Modify the 'role' column to set the default value to 'user'
            // The 'change()' method is crucial for modifying existing columns.
            $table->enum('role', ['admin', 'user', 'hr'])->default('user')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the change: remove the default value
            // Depending on your database system, you might need to set it back to nullable
            // or a specific default value. For this example, we'll set it back to nullable,
            // assuming it was potentially nullable before or you just want to remove the default.
            // If it was NOT nullable before, you might need to adjust this.
            $table->enum('role', ['admin', 'user', 'hr'])->nullable()->change(); // Or remove ->nullable() if it was not nullable originally
        });
    }
}

