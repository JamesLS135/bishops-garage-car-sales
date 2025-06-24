<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import the Rule class

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
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchases.create', compact('car', 'suppliers'));
    }

    /**
     * Store a newly created purchase record in storage.
     */
    public function store(Request $request, Car $car)
    {
        $request->merge(['is_imported_vehicle' => $request->has('is_imported_vehicle')]);
        $validatedData = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'purchase_price' => 'required|numeric|min:0',
            // ... all other validation rules from your existing store method
            'is_imported_vehicle' => 'nullable|boolean',
        ]);
        $validatedData['car_id'] = $car->id;
        Purchase::create($validatedData);
        return redirect()->route('cars.edit', $car->id)->with('success', 'Purchase details added successfully.');
    }

    /**
     * Show the form for editing the specified purchase.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\View\View
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::orderBy('name')->get();
        // The purchase model is automatically found by Laravel. We just pass it to the view.
        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    /**
     * Update the specified purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->merge(['is_imported_vehicle' => $request->has('is_imported_vehicle')]);

        $validatedData = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'purchase_price' => 'required|numeric|min:0',
            'odometer_at_purchase' => 'required|integer|min:0',
            'purchase_invoice_reference' => 'nullable|string|max:255',
            'is_imported_vehicle' => 'nullable|boolean',
            // --- NEW VALIDATION FOR REGISTRATION CHANGE ---
            'new_registration_number' => ['nullable', 'string', Rule::unique('cars', 'registration_number')->ignore($purchase->car_id)],
            'registration_change_date' => 'nullable|date',
        ]);

        // --- NEW LOGIC FOR REGISTRATION SWAP ---
        if ($request->filled('new_registration_number')) {
            // 1. If the original reg hasn't been saved yet, save the current one.
            if (!$purchase->original_registration_number) {
                $validatedData['original_registration_number'] = $purchase->car->registration_number;
            }
            // 2. Update the main car registration with the new one.
            $purchase->car()->update([
                'registration_number' => $request->new_registration_number
            ]);
        }

        $purchase->update($validatedData);

        return redirect()->route('cars.edit', $purchase->car_id)->with('success', 'Purchase details updated successfully.');
    }
}