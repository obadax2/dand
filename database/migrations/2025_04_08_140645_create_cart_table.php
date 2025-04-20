<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User ID foreign key
            $table->foreignId('item_id'); // Item ID, assuming it references another table (e.g., products, services)
            $table->string('item_type'); // Item type (if polymorphic)
            $table->unique(['user_id', 'item_id', 'item_type']);
            $table->decimal('price', 10, 2); // Price of the item
            $table->integer('quantity'); // Quantity of the item
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
        Schema::dropIfExists('cart');
    }
}