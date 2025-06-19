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
        Schema::create('cars', function (Blueprint $table) {
            $table->id(); // Standard Laravel auto-incrementing ID
            $table->string('vin', 17)->unique();
            $table->string('registration_number')->unique()->nullable();
            $table->string('make');
            $table->string('model');
            $table->string('variant_trim')->nullable();
            $table->year('year');
            $table->string('body_type')->nullable();
            $table->string('color')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('engine_size')->nullable();
            $table->string('transmission_type')->nullable();
            $table->integer('number_of_doors')->nullable();
            $table->integer('number_of_seats')->nullable();
            $table->integer('odometer_reading');
            $table->integer('number_of_keys_present')->default(1);
            $table->enum('tax_book_status', ['Present', 'Applied For', 'Missing', 'N/A'])->default('N/A');
            $table->enum('validated_status', ['Not Validated', 'VIN Verified', 'Docs Verified', 'Full Inspection Complete'])->default('Not Validated');
            $table->boolean('window_sticker_present')->default(false);
            $table->string('condition')->default('Used'); // Removed ENUM for simplicity, can be enforced in frontend
            $table->enum('current_status', ["In Stock - Available", "In Stock - Awaiting Prep", "In Stock - Awaiting VRT/NCT", "Sold", "Reserved", "In Service"])->default('In Stock - Awaiting Prep');
            $table->text('notes_internal')->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
