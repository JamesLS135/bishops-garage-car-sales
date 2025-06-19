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
        // Check if the 'role' column does NOT already exist before adding it.
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                // Add the new 'role' column after the 'name' column.
                // It will store the user's role as a string.
                // We set a default role of 'Sales' for any new users.
                $table->string('role')->after('name')->default('Sales');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the 'role' column EXISTS before trying to drop it.
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                // This defines how to undo the migration (by dropping the column).
                $table->dropColumn('role');
            });
        }
    }
};
