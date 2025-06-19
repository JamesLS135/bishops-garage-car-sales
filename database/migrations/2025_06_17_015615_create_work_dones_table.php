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
        Schema::create('work_dones', function (Blueprint $table) {
            $table->id('work_id');
            
            // Link to the car this work was performed on.
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            
            // Link to the user (mechanic) who performed the work.
            $table->foreignId('mechanic_id')->constrained('users')->onDelete('cascade');

            $table->date('work_date');
            $table->text('description');
            $table->text('parts_used')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_dones');
    }
};
