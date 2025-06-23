<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Sale;
use App\Models\Customer; // <-- Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Show the form for creating a new sale record for a specific car.
     */
    public function create(Car $car)
    {
        if (in_array($car->current_status, ['Sold', 'Reserved'])) {
             return redirect()->route('cars.index')->with('error', 'This car is not available for sale.');
        }

        $partExchangeCars = Car::where('current_status', 'In Stock - Available')
                               ->where('id', '!=', $car->id)
                               ->get();
        
        // Fetch all customers to populate the dropdown menu.
        $customers = Customer::orderBy('name')->get();

        return view('sales.create', compact('car', 'partExchangeCars', 'customers'));
    }

    /**
     * Store a newly created sale record in storage.
     */
    public function store(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'sale_date' => 'required|date',
            'selling_price' => 'required|numeric|min:0',
            'customer_id' => 'required|exists:customers,customer_id', // <-- Validate the ID
            'odometer_at_sale' => 'required|integer|min:0',
            'warranty_details' => 'nullable|string',
            'part_exchange_value' => 'nullable|numeric|min:0',
            'part_exchange_car_id' => 'nullable|exists:cars,id',
        ]);

        DB::beginTransaction();

        try {
            $sale = new Sale();
            $sale->car_id = $car->id;
            $sale->sale_date = $validatedData['sale_date'];
            $sale->selling_price = $validatedData['selling_price'];
            $sale->customer_id = $validatedData['customer_id'];
            $sale->odometer_at_sale = $validatedData['odometer_at_sale'];
            $sale->warranty_details = $validatedData['warranty_details'];
            $sale->salesperson_name = Auth::user()->name;

            if ($request->filled('part_exchange_car_id') && $request->filled('part_exchange_value')) {
                $partExchangeCar = Car::find($validatedData['part_exchange_car_id']);
                $customer = Customer::find($validatedData['customer_id']);

                if ($partExchangeCar && $customer) {
                    $sale->part_exchange_car_id = $partExchangeCar->id;
                    $sale->part_exchange_value = $validatedData['part_exchange_value'];

                    $partExchangeCar->purchase()->create([
                        'purchase_date' => $validatedData['sale_date'],
                        // Since we don't have a supplier for a part-exchange, we can use the customer's name
                        // or create a generic 'Part-Exchange' supplier.
                        // For now, let's use the customer's name as a placeholder.
                        'supplier_id' => null, // This assumes supplier_id is nullable
                        'purchase_price' => $validatedData['part_exchange_value'],
                        'odometer_at_purchase' => $partExchangeCar->odometer_reading,
                    ]);

                    $partExchangeCar->current_status = 'In Stock - Awaiting Prep';
                    $partExchangeCar->save();
                }
            }

            $sale->save();

            $car->current_status = 'Sold';
            $car->save();

            DB::commit();

            return redirect()->route('cars.index')->with('success', 'Car sold successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while processing the sale. Please try again.')->withInput();
        }
    }
}
