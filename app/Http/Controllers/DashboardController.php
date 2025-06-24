<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Sale;
use App\Models\Alert; // <-- ADD THIS LINE
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
        // --- FETCH ALL ALERTS ---
        // Get all alerts, ordered by the soonest due date first.
        // Eager-load the 'car' relationship so we can link to it.
        $alerts = Alert::with('car')->orderBy('due_date', 'asc')->get();

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

        // --- Cars Available for Sale List ---
        $availableCars = Car::where('current_status', 'In Stock - Available')
                           ->latest()
                           ->take(5)
                           ->get();


        // Pass all the calculated data to the view
        return view('dashboard', [
            'alerts' => $alerts, // <-- ADD THIS LINE
            'stockCounts' => $stockCounts,
            'salesThisMonth' => $salesThisMonth,
            'profitThisMonth' => $profitThisMonth,
            'carsAwaitingPrepCount' => $carsAwaitingPrepCount,
            'availableCars' => $availableCars,
        ]);
    }
}