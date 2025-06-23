<?php

namespace App\Observers;

use App\Models\WorkDone;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class WorkDoneObserver
{
    /**
     * Handle the WorkDone "created" event.
     */
    public function created(WorkDone $workDone): void
    {
        $workDone->load('car');

        $description = 'User ' . (Auth::user()->name ?? 'System') . ' added work record for car: ' . ($workDone->car->make ?? 'N/A') . ' ' . ($workDone->car->model ?? '') . ' (' . ($workDone->car->registration_number ?? '') . ').';

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'WORK_LOGGED',
            'table_name' => 'work_dones',
            'record_id' => $workDone->work_id,
            'description' => $description
        ]);
    }
}
