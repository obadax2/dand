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
    Schema::table('payment_log', function (Blueprint $table) {
        $table->string('transaction_id')->nullable()->change();
    });
}
    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('payment_log', function (Blueprint $table) {
        $table->string('transaction_id')->nullable(false)->change();
    });
}
};
