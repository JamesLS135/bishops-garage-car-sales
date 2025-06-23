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
        Schema::table('purchases', function (Blueprint $table) {
            // Add the new supplier_id column. It's nullable for now.
            $table->foreignId('supplier_id')->nullable()->after('purchase_date')->constrained('suppliers', 'supplier_id')->onDelete('set null');
            
            // Drop the old text-based column.
            $table->dropColumn('supplier_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Defines how to undo the change if we ever need to roll back.
            $table->string('supplier_name')->after('purchase_date');
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};
