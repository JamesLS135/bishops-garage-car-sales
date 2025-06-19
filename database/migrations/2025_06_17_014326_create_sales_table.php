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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('sale_id');

            // Link to the car being sold
            $table->foreignId('car_id')->constrained()->onDelete('cascade');

            $table->date('sale_date');
            $table->decimal('selling_price', 10, 2);
            $table->integer('odometer_at_sale');

            // We will add customer_id and salesperson_id later
            $table->string('customer_name'); // Temporary
            $table->string('salesperson_name'); // Temporary
            
            $table->text('warranty_details')->nullable();
            $table->string('sale_invoice_reference')->nullable();

            // --- Part-Exchange Details ---
            $table->decimal('part_exchange_value', 10, 2)->nullable();
            
            // Link to the car that was part-exchanged
            // This is nullable because not every sale has a part-exchange.
            $table->foreignId('part_exchange_car_id')
                  ->nullable()
                  ->constrained('cars', 'id') // Explicitly reference cars table and id column
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
