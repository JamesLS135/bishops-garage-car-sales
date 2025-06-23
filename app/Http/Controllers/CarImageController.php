<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarImageController extends Controller
{
    /**
     * Store a newly uploaded image for a car.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB Max
        ]);

        // Store the image in the 'public' disk, inside a 'car_images' folder.
        // This makes the images publicly accessible via a URL.
        $path = $request->file('image')->store('car_images', 'public');

        // Create the database record for the image.
        $image = $car->images()->create([
            'path' => $path,
        ]);
        
        // If this is the very first image for the car, automatically set it as primary.
        if ($car->images()->count() === 1) {
            $image->is_primary = true;
            $image->save();
        }

        return back()->with('success', 'Image uploaded successfully.');
    }

    /**
     * Remove the specified image from storage.
     *
     * @param  \App\Models\CarImage  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CarImage $image)
    {
        // Delete the physical file from the public storage disk.
        Storage::disk('public')->delete($image->path);

        // Delete the database record.
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Set the specified image as the primary image for its car.
     *
     * @param  \App\Models\CarImage  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPrimary(CarImage $image)
    {
        // First, set all other images for this car to not be primary.
        CarImage::where('car_id', $image->car_id)->update(['is_primary' => false]);

        // Then, set the selected image as primary.
        $image->is_primary = true;
        $image->save();

        return back()->with('success', 'Primary image updated.');
    }
}
