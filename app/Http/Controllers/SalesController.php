<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Show the form for creating a new sale record for a specific car.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\View\View
     */
    public function create(Car $car)
    {
        // Prevent selling a car that is already sold or reserved
        if (in_array($car->current_status, ['Sold', 'Reserved'])) {
             return redirect()->route('cars.index')->with('error', 'This car is not available for sale.');
        }

        // Fetch all available cars that could be used as a part-exchange
        // Exclude the car currently being sold
        $partExchangeCars = Car::where('current_status', 'In Stock - Available')
                               ->where('id', '!=', $car->id)
                               ->get();

        return view('sales.create', compact('car', 'partExchangeCars'));
    }

    /**
     * Store a newly created sale record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'sale_date' => 'required|date',
            'selling_price' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'odometer_at_sale' => 'required|integer|min:0',
            'warranty_details' => 'nullable|string',
            'part_exchange_value' => 'nullable|numeric|min:0',
            'part_exchange_car_id' => 'nullable|exists:cars,id',
        ]);

        // Use a database transaction to ensure all operations succeed or none do.
        DB::beginTransaction();

        try {
            // Step 1: Create the Sale record
            $sale = new Sale();
            $sale->car_id = $car->id;
            $sale->sale_date = $validatedData['sale_date'];
            $sale->selling_price = $validatedData['selling_price'];
            $sale->customer_name = $validatedData['customer_name'];
            $sale->odometer_at_sale = $validatedData['odometer_at_sale'];
            $sale->warranty_details = $validatedData['warranty_details'];
            $sale->salesperson_name = Auth::user()->name; // Get the logged-in user's name

            // Step 2: Handle the part-exchange logic
            if ($request->filled('part_exchange_car_id') && $request->filled('part_exchange_value')) {
                $partExchangeCar = Car::find($validatedData['part_exchange_car_id']);

                if ($partExchangeCar) {
                    $sale->part_exchange_car_id = $partExchangeCar->id;
                    $sale->part_exchange_value = $validatedData['part_exchange_value'];

                    // Create a new purchase record for the part-exchanged car
                    $partExchangeCar->purchase()->create([
                        'purchase_date' => $validatedData['sale_date'],
                        'supplier_name' => $validatedData['customer_name'], // The customer is the supplier
                        'purchase_price' => $validatedData['part_exchange_value'],
                        'odometer_at_purchase' => $partExchangeCar->odometer_reading, // Use current reading
                    ]);

                    // Update the status of the part-exchanged car
                    $partExchangeCar->current_status = 'In Stock - Awaiting Prep';
                    $partExchangeCar->save();
                }
            }

            $sale->save();

            // Step 3: Update the sold car's status
            $car->current_status = 'Sold';
            $car->save();

            // If all operations were successful, commit the transaction
            DB::commit();

            return redirect()->route('cars.index')->with('success', 'Car sold successfully!');

        } catch (\Exception $e) {
            // If any operation fails, roll back the transaction
            DB::rollBack();

            // Log the error for debugging
            // \Log::error($e);

            return redirect()->back()->with('error', 'An error occurred while processing the sale. Please try again.')->withInput();
        }
    }
}
