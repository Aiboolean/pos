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
        $table->decimal('price', 8, 2);
        $table->boolean('is_available')->default(true);
        $table->string('image')->nullable(); // Store image path
        $table->string('category')->nullable(); // Add this line for the category column
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
