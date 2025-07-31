<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
    $table->id();
    $table->string('name');           // "Coffee Beans", "Milk"
    $table->string('unit');           // "g", "ml", "oz"
    $table->decimal('stock', 10, 2);  // Current stock (500.00)
    $table->decimal('alert_threshold', 10, 2)->nullable(); // Optional
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
