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
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('amount_received', 8, 2)->nullable(); // Add amount_received column
        $table->decimal('change', 8, 2)->nullable(); // Add change column
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('amount_received'); // Rollback: remove amount_received column
        $table->dropColumn('change'); // Rollback: remove change column
    });
}
};
