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
            $table->id(); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('item_id'); 
            $table->string('item_type'); 
            $table->unique(['user_id', 'item_id', 'item_type']);
            $table->decimal('price', 10, 2); 
            $table->integer('quantity'); 
            $table->timestamps(); 
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