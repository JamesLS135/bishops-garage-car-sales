<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the new 'role' column after the 'email' column.
            // It will store the user's role and defaults to 'Sales'.
            $table->string('role')->after('email')->default('Sales');
        });

        // After adding the column, find the first user (ID = 1) and make them an Admin.
        // This ensures you have an admin account to start with.
        $firstUser = User::find(1);
        if ($firstUser) {
            $firstUser->role = 'Admin';
            $firstUser->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This defines how to undo the migration (remove the 'role' column).
            $table->dropColumn('role');
        });
    }
};
