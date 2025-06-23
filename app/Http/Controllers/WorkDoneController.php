<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\WorkDone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkDoneController extends Controller
{
    /**
     * Show the form for creating a new work record for a specific car.
     */
    public function create(Car $car)
    {
        return view('work.create', compact('car'));
    }

    /**
     * Store a newly created work record in storage.
     */
    public function store(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'work_date' => 'required|date',
            'description' => 'required|string',
            'parts_used' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        $validatedData['car_id'] = $car->id;
        $validatedData['mechanic_id'] = Auth::id();

        WorkDone::create($validatedData);
        
        return redirect()->route('cars.edit', $car)->with('success', 'Work record added successfully.');
    }

    /**
     * Display the specified work record.
     * The {workDone} parameter is automatically resolved to a WorkDone instance.
     * @param  \App\Models\WorkDone  $workDone
     * @return \Illuminate\View\View
     */
    public function show(WorkDone $workDone)
    {
        // Load the related car and mechanic information for display
        $workDone->load(['car', 'mechanic']);

        // Return the new 'show' view and pass the work record to it.
        return view('work.show', compact('workDone'));
    }
}
