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
    Schema::table('ticket_messages', function (Blueprint $table) {
        $table->boolean('is_read')->default(false)->after('sender');
    });
}

public function down()
{
    Schema::table('ticket_messages', function (Blueprint $table) {
        $table->dropColumn('is_read');
    });
}
};
