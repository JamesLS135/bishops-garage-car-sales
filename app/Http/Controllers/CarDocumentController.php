<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarDocument; // Assuming you have a CarDocument model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarDocumentController extends Controller
{
    /**
     * Store a newly uploaded document for a car.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'document_type' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB Max
        ]);

        $file = $request->file('document_file');
        
        $path = $file->store('car_documents/' . $car->id, 'local');

        // Create a record in the database for the new document.
        $car->documents()->create([
            'document_type' => $request->document_type,
            'description' => $request->description,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'uploaded_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display a document inline in the browser.
     * The {document} parameter is automatically resolved to a CarDocument instance.
     */
    public function show(CarDocument $document)
    {
        // This method will now handle viewing the file in the browser.
        // The response() method sets the correct headers to display inline.
        return Storage::disk('local')->response($document->file_path, $document->file_name);
    }
    
    /**
     * Handle the request to download a document.
     * Kept for legacy purposes in case it was routed somewhere.
     */
    public function download(CarDocument $document)
    {
        // For consistency, this now uses the show method's logic.
        // To force a download, you would use Storage::disk('local')->download(...)
        return $this->show($document);
    }

    /**
     * Remove the specified document from storage and the database.
     *
     * @param  \App\Models\CarDocument  $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CarDocument $document)
    {
        // First, delete the physical file from the storage disk.
        Storage::disk('local')->delete($document->file_path);
        
        // Then, delete the record from the database.
        $document->delete();
        
        // Redirect back to the previous page with a success message.
        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
