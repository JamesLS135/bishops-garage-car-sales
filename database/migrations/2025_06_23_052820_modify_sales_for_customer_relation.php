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
        Schema::table('sales', function (Blueprint $table) {
            // Add the new customer_id column. It's nullable for now.
            $table->foreignId('customer_id')->nullable()->after('selling_price')->constrained('customers', 'customer_id')->onDelete('set null');

            // Drop the old text-based column.
            $table->dropColumn('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Defines how to undo the change if we ever need to roll back.
            $table->string('customer_name')->after('selling_price');
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
