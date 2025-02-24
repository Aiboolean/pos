<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key to categories
        $table->decimal('price_small', 8, 2)->nullable();
        $table->decimal('price_medium', 8, 2)->nullable();
        $table->decimal('price_large', 8, 2)->nullable();
        $table->decimal('price', 8, 2)->nullable(); // Single price for non-size categories
        $table->boolean('is_available')->default(true);
        $table->string('image')->nullable();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
