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
        // Check if the 'activity_log' table does NOT already exist before creating it.
        if (!Schema::hasTable('activity_log')) {
            Schema::create('activity_log', function (Blueprint $table) {
                $table->id('log_id');
                
                // Link to the user who performed the action. Can be null for system actions.
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

                $table->string('action_type'); // e.g., 'CREATED', 'UPDATED', 'DELETED'
                $table->string('table_name');  // e.g., 'cars', 'sales'
                $table->unsignedBigInteger('record_id'); // The ID of the affected record (e.g., car_id)
                
                $table->text('description'); // A human-readable description of the event.
                
                $table->timestamps(); // Adds created_at and updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
