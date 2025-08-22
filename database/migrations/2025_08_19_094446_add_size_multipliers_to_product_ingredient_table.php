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
        Schema::table('product_ingredient', function (Blueprint $table) {
            $table->decimal('small_multiplier', 5, 2)->default(0.75)->after('quantity');
            $table->decimal('medium_multiplier', 5, 2)->default(1.00)->after('small_multiplier');
            $table->decimal('large_multiplier', 5, 2)->default(1.50)->after('medium_multiplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_ingredient', function (Blueprint $table) {
            //
        });
    }
};
