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
            if (!Schema::hasColumn('product_ingredient', 'small_multiplier')) {
                $table->decimal('small_multiplier', 5, 2)->default(0.75)->after('quantity');
            }
            if (!Schema::hasColumn('product_ingredient', 'medium_multiplier')) {
                $table->decimal('medium_multiplier', 5, 2)->default(1.00)->after('small_multiplier');
            }
            if (!Schema::hasColumn('product_ingredient', 'large_multiplier')) {
                $table->decimal('large_multiplier', 5, 2)->default(1.50)->after('medium_multiplier');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_ingredient', function (Blueprint $table) {
            if (Schema::hasColumn('product_ingredient', 'small_multiplier')) {
                $table->dropColumn('small_multiplier');
            }
            if (Schema::hasColumn('product_ingredient', 'medium_multiplier')) {
                $table->dropColumn('medium_multiplier');
            }
            if (Schema::hasColumn('product_ingredient', 'large_multiplier')) {
                $table->dropColumn('large_multiplier');
            }
        });
    }
};
