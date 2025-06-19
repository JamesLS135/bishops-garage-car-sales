<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the profitability report.
     *
     * @return \Illuminate\View\View
     */
    public function profitability(Request $request)
    {
        // Start a query for cars that have a sale record (i.e., are sold).
        // We use 'has('sale')' to filter only sold cars.
        $query = Car::whereHas('sale');

        // Eager load the relationships to prevent N+1 query problems.
        // This is much more efficient than loading them one by one.
        $query->with(['purchase', 'sale', 'workDone']);

        // Order by the most recent sale date first.
        $query->join('sales', 'cars.id', '=', 'sales.car_id')
              ->orderBy('sales.sale_date', 'desc')
              ->select('cars.*'); // Ensure we select all columns from the cars table

        // Paginate the results to keep the report page fast.
        $soldCars = $query->paginate(20);

        // Return the report view and pass the collection of sold cars to it.
        return view('reports.profitability', compact('soldCars'));
    }
}
