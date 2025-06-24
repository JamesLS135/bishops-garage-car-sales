<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\WorkDone; // Import the WorkDone model
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the profitability report.
     */
    public function profitability(Request $request)
    {
        // ... (existing method is unchanged) ...
        $query = Car::whereHas('sale');
        $query->with(['purchase', 'sale', 'workDone']);
        $query->join('sales', 'cars.id', '=', 'sales.car_id')
              ->orderBy('sales.sale_date', 'desc')
              ->select('cars.*');
        $soldCars = $query->paginate(20);
        return view('reports.profitability', compact('soldCars'));
    }

    /**
     * Display the inventory report.
     */
    public function inventory(Request $request)
    {
        // ... (existing method is unchanged) ...
        $query = Car::query()
            ->where('current_status', '!=', 'Sold')
            ->whereHas('purchase')
            ->with(['purchase', 'workDone'])
            ->orderBy(
                Purchase::select('purchase_date')
                    ->whereColumn('purchases.car_id', 'cars.id')
            , 'asc');
        $stockCars = $query->paginate(25);
        return view('reports.inventory', compact('stockCars'));
    }

    /**
     * Display the sales history report with date filtering.
     */
    public function salesHistory(Request $request)
    {
        // ... (existing method is unchanged) ...
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30);
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : now();
        $salesQuery = Sale::query()
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->with('car');
        $summaryQuery = clone $salesQuery;
        $sales = $salesQuery->orderBy('sale_date', 'desc')->paginate(25)->withQueryString();
        $periodSales = $summaryQuery->get();
        $totalRevenue = $periodSales->sum('selling_price');
        $totalPartExchange = $periodSales->sum('part_exchange_value');
        $totalProfit = $periodSales->sum(fn($sale) => $sale->car ? $sale->car->profitMargin : 0);

        return view('reports.sales-history', [
            'sales' => $sales,
            'totalRevenue' => $totalRevenue,
            'totalPartExchange' => $totalPartExchange,
            'totalProfit' => $totalProfit,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }

    /**
     * Display the work done history report with date filtering.
     *
     * @return \Illuminate\View\View
     */
    public function workDoneHistory(Request $request)
    {
        // Validate the date inputs
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Set default date range to the last 30 days if nothing is provided
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : now()->subDays(30);
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : now();

        // Query work done records within the date range
        $workDoneQuery = WorkDone::query()
            ->whereBetween('work_date', [$startDate, $endDate])
            ->with(['car', 'mechanic']); // Eager load relationships

        // Clone for summary calculation
        $summaryQuery = clone $workDoneQuery;

        // Get paginated results for display
        $workDoneItems = $workDoneQuery->orderBy('work_date', 'desc')->paginate(25)->withQueryString();

        // Calculate total cost for the period
        $totalCost = $summaryQuery->sum('cost');
        
        return view('reports.work-done-history', [
            'workDoneItems' => $workDoneItems,
            'totalCost' => $totalCost,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]);
    }
}