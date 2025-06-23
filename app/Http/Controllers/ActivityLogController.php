<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity log entries.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all logs from the database, ordered by the most recent first.
        // We use with('user') to eager load the user who performed the action, which is more efficient.
        // We paginate the results to keep the page fast and easy to navigate.
        $logs = ActivityLog::with('user')->latest()->paginate(25);

        // CORRECTED VIEW PATH: We now point to 'activity-log.index'
        return view('activity-log.index', compact('logs'));
    }
}
