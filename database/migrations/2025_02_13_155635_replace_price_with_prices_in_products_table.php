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
    Schema::table('products', function (Blueprint $table) {
        // Remove the old price column
        $table->dropColumn('price');

        // Add the new prices column (JSON to store prices for sizes)
        $table->json('prices')->nullable()->after('category');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        // Revert the changes if needed
        $table->decimal('price', 8, 2)->after('category'); // Re-add the old price column
        $table->dropColumn('prices'); // Drop the new prices column
    });
}
};
