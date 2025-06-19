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
        Schema::create('car_documents', function (Blueprint $table) {
            $table->id('doc_id');
            
            // Link to the car this document belongs to.
            $table->foreignId('car_id')->constrained()->onDelete('cascade');

            $table->enum('document_type', [
                'Autel Report', 
                'Purchase Invoice', 
                'Sales Invoice', 
                'NCT Certificate', 
                'VRT Receipt', 
                'Warranty', 
                'Other'
            ]);
            
            // The path where the file is stored on the server.
            $table->string('file_path');
            
            // The original name of the uploaded file.
            $table->string('file_name');
            
            $table->text('description')->nullable();

            // Link to the user who uploaded the document.
            $table->foreignId('uploaded_by_user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_documents');
    }
};
