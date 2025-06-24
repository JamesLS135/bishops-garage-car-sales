<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarController extends Controller
{
    /**
     * Display a listing of the resource, with optional status and search filtering.
     */
    public function index(Request $request)
    {
        // Start a query for cars, order by the most recently created
        $query = Car::latest();

        // --- ADVANCED FILTERING LOGIC ---
        // Filter by Make
        if ($request->filled('make')) {
            $query->where('make', 'LIKE', '%' . $request->make . '%');
        }

        // Filter by Model
        if ($request->filled('model')) {
            $query->where('model', 'LIKE', '%' . $request->model . '%');
        }

        // Filter by Year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        // --- STATUS FILTERING LOGIC ---
        // Check if a 'status' parameter is present in the URL from the dashboard link
        if ($request->has('status') && $request->status != '') {
            $query->where('current_status', $request->status);
        }
        
        // Paginate the results to keep the list manageable
        // withQueryString() appends all current filter parameters to the pagination links
        $cars = $query->paginate(15)->withQueryString();

        // Pass the car data and all filter terms to the view
        return view('cars.index', [
            'cars' => $cars,
            'filters' => $request->only(['make', 'model', 'year', 'status']), // Pass all active filters to the view
        ]);
    }

    /**
     * Show the form for creating a new car.
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created car in the database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vin' => 'required|string|size:17|unique:cars,vin',
            'registration_number' => 'required|string|unique:cars,registration_number',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'odometer_reading' => 'required|integer|min:0',
            'current_status' => 'required|string',
        ]);

        Car::create($validatedData);

        return redirect()->route('cars.index')->with('success', 'Car added successfully.');
    }

    /**
     * Display the specified resource.
     * For now, we'll just redirect to the edit page.
     */
    public function show(Car $car)
    {
        return redirect()->route('cars.edit', $car);
    }

    /**
     * Show the form for editing the specified car.
     */
    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified car in the database.
     */
    public function update(Request $request, Car $car)
    {
        $validatedData = $request->validate([
            'vin' => 'required|string|size:17|unique:cars,vin,' . $car->id,
            'registration_number' => 'required|string|unique:cars,registration_number,' . $car->id,
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'odometer_reading' => 'required|integer|min:0',
            'current_status' => 'required|string',
        ]);

        $car->update($validatedData);

        return redirect()->route('cars.index')->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified car from the database.
     */
    public function destroy(Car $car)
    {
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Car deleted successfully.');
    }

    /**
     * Generate a PDF cover sheet for the specified car.
     */
    public function generateCoverSheet(Car $car)
    {
        $qrCode = QrCode::size(150)->generate(route('cars.edit', $car->id));

        $pdf = Pdf::loadView('pdf.cover_sheet', [
            'car' => $car,
            'qrCode' => $qrCode
        ]);

        $filename = 'CoverSheet-' . $car->registration_number . '.pdf';
        return $pdf->stream($filename);
    }
}