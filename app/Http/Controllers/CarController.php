<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Add this for PDF generation
use SimpleSoftwareIO\QrCode\Facades\QrCode; // <-- Add this for QR code generation

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     * This will be the main car inventory page.
     */
    public function index()
    {
        // Fetch all cars from the database, order by the newest first, and paginate them.
        $cars = Car::latest()->paginate(15); 

        // Return the 'index' view and pass the car data to it.
        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new car.
     */
    public function create()
    {
        // Simply return the view that contains the "add new car" form.
        return view('cars.create');
    }

    /**
     * Store a newly created car in the database.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data based on our rules.
        $validatedData = $request->validate([
            'vin' => 'required|string|size:17|unique:cars,vin',
            'registration_number' => 'required|string|unique:cars,registration_number',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'odometer_reading' => 'required|integer|min:0',
            'current_status' => 'required|string',
        ]);

        // Create a new Car instance with the validated data.
        Car::create($validatedData);

        // Redirect the user back to the main car list with a success message.
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
     * Laravel's Route Model Binding automatically finds the car by its ID.
     */
    public function edit(Car $car)
    {
        // Return the 'edit' view and pass the specific car's data to it.
        return view('cars.edit', compact('car'));
    }

    /**
     * Update the specified car in the database.
     */
    public function update(Request $request, Car $car)
    {
        // Validate the incoming request data. Note the rule for VIN is slightly different on update.
        $validatedData = $request->validate([
            'vin' => 'required|string|size:17|unique:cars,vin,' . $car->id,
            'registration_number' => 'required|string|unique:cars,registration_number,' . $car->id,
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'odometer_reading' => 'required|integer|min:0',
            'current_status' => 'required|string',
        ]);

        // Update the car's attributes with the validated data.
        $car->update($validatedData);

        // Redirect the user back to the main car list with a success message.
        return redirect()->route('cars.index')->with('success', 'Car updated successfully.');
    }

    /**
     * Remove the specified car from the database.
     */
    public function destroy(Car $car)
    {
        // Delete the car record.
        $car->delete();

        // Redirect the user back to the main car list with a success message.
        return redirect()->route('cars.index')->with('success', 'Car deleted successfully.');
    }

    /**
     * Generate a PDF cover sheet for the specified car.
     */
    public function generateCoverSheet(Car $car)
    {
        // Generate a QR code that links to the car's edit page.
        $qrCode = QrCode::size(150)->generate(route('cars.edit', $car->id));

        // Load the view and pass the car data and the QR code to it.
        $pdf = Pdf::loadView('pdf.cover_sheet', [
            'car' => $car,
            'qrCode' => $qrCode
        ]);

        // Set a filename and stream the PDF to the browser for download.
        $filename = 'CoverSheet-' . $car->registration_number . '.pdf';
        return $pdf->stream($filename);
    }
}
