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
        Schema::create('car_images', function (Blueprint $table) {
            $table->id();
            
            // Link to the car this image belongs to.
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            
            // Stores the path to the image file in the storage directory.
            $table->string('path');

            // A flag to mark one image as the primary "hero" image.
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_images');
    }
};
