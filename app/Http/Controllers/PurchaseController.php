<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Purchase;
use App\Models\Supplier; // <-- Add this line
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Show the form for creating new purchase details for a specific car.
     */
    public function create(Car $car)
    {
        if ($car->purchase) {
            return redirect()->route('cars.edit', $car)->with('error', 'Purchase details already exist for this car.');
        }

        // Fetch all suppliers to populate the dropdown menu.
        $suppliers = Supplier::orderBy('name')->get();

        // Pass the car and the list of suppliers to the view.
        return view('purchases.create', compact('car', 'suppliers'));
    }

    /**
     * Store a newly created purchase record in storage.
     */
    public function store(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,supplier_id', // <-- Validate the ID
            'purchase_price' => 'required|numeric|min:0',
            'odometer_at_purchase' => 'required|integer|min:0',
            'purchase_invoice_reference' => 'nullable|string|max:255',
            'is_imported_vehicle' => 'nullable|boolean',
            'vrt_paid_amount' => 'nullable|numeric|min:0',
            'vrt_payment_date' => 'nullable|date',
            'nct_status' => 'nullable|string',
            'nct_test_date' => 'nullable|date',
            'nct_certificate_number' => 'nullable|string|max:255',
            'nct_cost' => 'nullable|numeric|min:0',
            'import_duty_amount' => 'nullable|numeric|min:0',
            'transport_cost_import' => 'nullable|numeric|min:0',
            'other_expenses_total' => 'nullable|numeric|min:0',
            'purchase_notes' => 'nullable|string',
        ]);

        $validatedData['car_id'] = $car->id;
        $validatedData['is_imported_vehicle'] = $request->has('is_imported_vehicle');

        Purchase::create($validatedData);

        return redirect()->route('cars.index')->with('success', 'Purchase details added successfully for ' . $car->registration_number);
    }
}
