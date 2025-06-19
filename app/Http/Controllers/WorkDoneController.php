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
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\View\View
     */
    public function create(Car $car)
    {
        // Return the view that contains the "add work done" form.
        // Pass the car object to the view so we know which car we're working on.
        return view('work.create', compact('car'));
    }

    /**
     * Store a newly created work record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Car $car)
    {
        // Validate the incoming form data.
        $validatedData = $request->validate([
            'work_date' => 'required|date',
            'description' => 'required|string',
            'parts_used' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        // Add the car_id and the id of the logged-in user (mechanic) to the data array.
        $validatedData['car_id'] = $car->id;
        $validatedData['mechanic_id'] = Auth::id();

        // Create the new WorkDone record in the database.
        WorkDone::create($validatedData);

        // Redirect the user back to the car's edit page with a success message.
        // This is a good user experience as they'll likely want to see the car's details after adding work.
        return redirect()->route('cars.edit', $car)->with('success', 'Work record added successfully.');
    }
}
