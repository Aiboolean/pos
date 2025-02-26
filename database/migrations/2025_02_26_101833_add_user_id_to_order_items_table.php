<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddUserIdToOrderItemsTable extends Migration
{
    public function up()
    {
        // Add the user_id column (nullable initially to avoid errors)
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // Assign a default user_id to existing rows (e.g., the first user in the users table)
        $defaultUserId = DB::table('users')->value('id'); // Get the first user's ID
        DB::table('order_items')->update(['user_id' => $defaultUserId]);

        // Add the foreign key constraint
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drop the foreign key constraint
            $table->dropColumn('user_id'); // Drop the user_id column
        });
    }
}
