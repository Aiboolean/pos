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
        $table->boolean('small_enabled')->default(true)->after('price_small');
        $table->boolean('medium_enabled')->default(true)->after('price_medium');
        $table->boolean('large_enabled')->default(true)->after('price_large');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['small_enabled', 'medium_enabled', 'large_enabled']);
    });
}
};