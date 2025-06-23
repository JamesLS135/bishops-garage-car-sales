<?php

namespace App\Observers;

use App\Models\Purchase;
use App\Models\ActivityLog; // <-- Add this line
use Illuminate\Support\Facades\Auth; // <-- Add this line

class PurchaseObserver
{
    /**
     * Handle the Purchase "created" event.
     */
    public function created(Purchase $purchase): void
    {
        // We need to load the 'car' relationship to get its details for the description.
        $purchase->load('car');

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'CREATED',
            'table_name' => 'purchases',
            'record_id' => $purchase->purchase_id,
            'description' => 'User ' . (Auth::user()->name ?? 'System') . ' added purchase details for car: ' . ($purchase->car->make ?? 'N/A') . ' ' . ($purchase->car->model ?? '') . ' (' . ($purchase->car->registration_number ?? '') . ').'
        ]);
    }

    /**
     * Handle the Purchase "updated" event.
     */
    public function updated(Purchase $purchase): void
    {
        // We can add logic here later if needed
    }

    /**
     * Handle the Purchase "deleted" event.
     */
    public function deleted(Purchase $purchase): void
    {
        // We can add logic here later if needed
    }

    /**
     * Handle the Purchase "restored" event.
     */
    public function restored(Purchase $purchase): void
    {
        //
    }

    /**
     * Handle the Purchase "force deleted" event.
     */
    public function forceDeleted(Purchase $purchase): void
    {
        //
    }
}
