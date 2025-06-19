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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchase_id');
            
            // Link to the car this purchase record belongs to.
            // Using constrained() automatically links to the 'cars' table's 'id' column.
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            
            $table->date('purchase_date');

            // We will add the supplier_id foreign key later when we create the suppliers table
            $table->string('supplier_name'); // Temporary until suppliers table is made
            
            $table->decimal('purchase_price', 10, 2);
            $table->integer('odometer_at_purchase');
            $table->string('purchase_invoice_reference')->nullable();
            
            // --- Imported Car Specifics ---
            $table->boolean('is_imported_vehicle')->default(false);
            $table->decimal('vrt_paid_amount', 10, 2)->nullable();
            $table->date('vrt_payment_date')->nullable();
            $table->enum('nct_status', ['Not Applicable', 'Due', 'Booked', 'Passed', 'Failed - Rework Needed'])->nullable();
            $table->date('nct_test_date')->nullable();
            $table->string('nct_certificate_number')->nullable();
            $table->decimal('nct_cost', 10, 2)->nullable();
            $table->decimal('import_duty_amount', 10, 2)->nullable();
            $table->decimal('transport_cost_import', 10, 2)->nullable();

            // --- Other Expenses ---
            $table->decimal('other_expenses_total', 10, 2)->nullable();

            $table->text('purchase_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
