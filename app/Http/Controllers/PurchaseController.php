<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Show the form for creating new purchase details for a specific car.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\View\View
     */
    public function create(Car $car)
    {
        // Check if the car already has a purchase record to prevent duplicates.
        if ($car->purchase) {
            return redirect()->route('cars.edit', $car)->with('error', 'Purchase details already exist for this car.');
        }

        // Return the view for the 'add purchase' form and pass the specific car object to it.
        return view('purchases.create', compact('car'));
    }

    /**
     * Store a newly created purchase record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Car $car)
    {
        // Validate the incoming form data.
        // We have removed the 'is_imported_vehicle' => 'nullable|boolean' rule from here.
        $validatedData = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'odometer_at_purchase' => 'required|integer|min:0',
            'purchase_invoice_reference' => 'nullable|string|max:255',
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

        // Add the car_id to the validated data array.
        $validatedData['car_id'] = $car->id;

        // Handle the checkbox for 'is_imported_vehicle'. This correctly returns true or false.
        $validatedData['is_imported_vehicle'] = $request->has('is_imported_vehicle');

        // Create the new Purchase record in the database.
        Purchase::create($validatedData);

        // Redirect the user back to the main car list with a success message.
        return redirect()->route('cars.index')->with('success', 'Purchase details added successfully for ' . $car->registration_number);
    }
}
