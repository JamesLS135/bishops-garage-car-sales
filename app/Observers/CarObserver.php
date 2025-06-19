<?php

namespace App\Observers;

use App\Models\Car;
use App\Models\ActivityLog; // We will create this model next
use Illuminate\Support\Facades\Auth;

class CarObserver
{
    /**
     * Handle the Car "created" event.
     */
    public function created(Car $car): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'CREATE',
            'table_name' => 'cars',
            'record_id' => $car->id,
            'description' => "Created new car: {$car->make} {$car->model} ({$car->registration_number})",
        ]);
    }

    /**
     * Handle the Car "updated" event.
     */
    public function updated(Car $car): void
    {
        // Get a list of what has changed.
        $changes = $car->getDirty();
        $description = "Updated car: {$car->make} {$car->model}. ";
        
        // Build a description string from the changes.
        foreach ($changes as $field => $newValue) {
            if ($field !== 'updated_at') { // Don't log timestamp changes
                $oldValue = $car->getOriginal($field);
                $description .= "Changed '{$field}' from '{$oldValue}' to '{$newValue}'. ";
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'UPDATE',
            'table_name' => 'cars',
            'record_id' => $car->id,
            'description' => $description,
        ]);
    }

    /**
     * Handle the Car "deleted" event.
     */
    public function deleted(Car $car): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'DELETE',
            'table_name' => 'cars',
            'record_id' => $car->id,
            'description' => "Deleted car: {$car->make} {$car->model} ({$car->registration_number})",
        ]);
    }
}
