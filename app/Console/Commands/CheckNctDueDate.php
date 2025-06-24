<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Car;
use App\Models\Alert;
use Carbon\Carbon;

class CheckNctDueDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-nct-due-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for cars with NCT dates expiring soon and creates alerts.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for NCT due dates...');

        // First, clear any existing NCT alerts to avoid duplicates.
        Alert::where('type', 'nct_due')->delete();

        // Define the time window (e.g., alert for NCTs due in the next 30 days).
        $warningThreshold = now()->addDays(30);

        // Find all non-sold cars that have an NCT test date within our warning window.
        $carsWithNctDue = Car::where('current_status', '!=', 'Sold')
            ->whereHas('purchase', function ($query) use ($warningThreshold) {
                $query->whereNotNull('nct_test_date')
                      ->where('nct_test_date', '<=', $warningThreshold);
            })
            ->with('purchase') // Eager load the purchase data
            ->get();

        if ($carsWithNctDue->isEmpty()) {
            $this->info('No upcoming NCTs found.');
            return;
        }

        foreach ($carsWithNctDue as $car) {
            $dueDate = $car->purchase->nct_test_date;
            
            // Skip if the NCT date is in the past (already expired) by more than a year to avoid old alerts
            if ($dueDate->isBefore(now()->subYear())) {
                continue;
            }

            $daysRemaining = now()->diffInDays($dueDate, false); // `false` allows for negative numbers (overdue)

            // Determine the alert level
            if ($daysRemaining < 0) {
                $level = 'danger'; // Overdue
                $message = "NCT for car {$car->registration_number} was due " . abs($daysRemaining) . " days ago.";
            } elseif ($daysRemaining <= 7) {
                $level = 'danger'; // Due very soon
                $message = "NCT for car {$car->registration_number} is due in {$daysRemaining} days.";
            } else {
                $level = 'warning'; // Due in the near future
                $message = "NCT for car {$car->registration_number} is due in {$daysRemaining} days.";
            }

            // Create the alert in the database
            Alert::create([
                'car_id' => $car->id,
                'type' => 'nct_due',
                'message' => $message,
                'due_date' => $dueDate,
                'level' => $level,
            ]);

            $this->info("Alert created for car: {$car->registration_number}");
        }

        $this->info('NCT due date check complete.');
    }
}