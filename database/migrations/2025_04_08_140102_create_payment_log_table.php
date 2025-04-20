<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_log', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User ID foreign key
            $table->decimal('amount', 10, 2); // Amount column, with precision and scale
            $table->string('payment_method'); // Payment method column
            $table->string('transaction_id')->unique(); // Unique transaction ID
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']); // Status column with specified values
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
        Schema::dropIfExists('payment_log');
    }
}