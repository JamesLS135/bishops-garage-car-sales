<?php

namespace App\Observers;

use App\Models\Sale;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class SaleObserver
{
    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        // Load the related car to get its details for the log description
        $sale->load('car');

        $description = 'User ' . (Auth::user()->name ?? 'System') . ' sold car: ' . ($sale->car->make ?? 'N/A') . ' ' . ($sale->car->model ?? '') . ' (' . ($sale->car->registration_number ?? '') . ').';

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'SOLD',
            'table_name' => 'sales',
            'record_id' => $sale->sale_id,
            'description' => $description
        ]);
    }

    /**
     * Handle the Sale "updated" event.
     */
    public function updated(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "deleted" event.
     */
    public function deleted(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "restored" event.
     */
    public function restored(Sale $sale): void
    {
        //
    }

    /**
     * Handle the Sale "force deleted" event.
     */
    public function forceDeleted(Sale $sale): void
    {
        //
    }
}
