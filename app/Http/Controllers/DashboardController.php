<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard with interactive stats.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // --- Stat Card 1: Stock Counts ---
        $stockCounts = Car::query()
            ->select('current_status', DB::raw('count(*) as total'))
            ->whereIn('current_status', ['In Stock - Available', 'In Stock - Awaiting Prep', 'In Stock - Awaiting VRT/NCT', 'Reserved'])
            ->groupBy('current_status')
            ->pluck('total', 'current_status');

        // --- Stat Card 2: Sales This Month ---
        $salesThisMonth = Sale::whereYear('sale_date', Carbon::now()->year)
                              ->whereMonth('sale_date', Carbon::now()->month)
                              ->count();

        // --- Stat Card 3: Profit This Month ---
        $carsSoldThisMonth = Car::whereHas('sale', function ($query) {
            $query->whereYear('sale_date', Carbon::now()->year)
                  ->whereMonth('sale_date', Carbon::now()->month);
        })->get();
        $profitThisMonth = $carsSoldThisMonth->sum('profit_margin');

        // --- Stat Card 4: Cars Awaiting Prep ---
        $carsAwaitingPrepCount = $stockCounts->get('In Stock - Awaiting Prep', 0);

        // --- NEW: Cars Available for Sale List ---
        $availableCars = Car::where('current_status', 'In Stock - Available')
                            ->latest() // Get the most recently added cars first
                            ->take(5)
                            ->get();


        // Pass all the calculated data to the view
        return view('dashboard', [
            'stockCounts' => $stockCounts,
            'salesThisMonth' => $salesThisMonth,
            'profitThisMonth' => $profitThisMonth,
            'carsAwaitingPrepCount' => $carsAwaitingPrepCount,
            'availableCars' => $availableCars, // Changed from 'recentSales'
        ]);
    }
}
