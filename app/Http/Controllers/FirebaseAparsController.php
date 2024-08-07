<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAparsController extends Controller
{
    public function index()
    {
        try {
            $apartments = Apartment::with('images')->get(); // Eager load images
            return response()->json($apartments);
        } catch (\Exception $e) {
            Log::error('Error fetching apartments: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching apartments'], 500);
        }
    }

    public function store(Request $request)
{
    Log::warning("Request data: " . json_encode($request->all()));
    
    Log::warning("Storing apartment");

    try {
        Log::warning("I'm here now");

        $apartmentData = $request->only(['name', 'description', 'price', 'city_id', 'address']);
        $apartment = Apartment::create($apartmentData);

        Log::warning("Apartment created with ID: " . $apartment->id);

        // Debugging the image file
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            Log::warning("Images field: " . json_encode($images));

            // If `images` is a single file
            if ($images instanceof \Illuminate\Http\UploadedFile) {
                Log::warning("Single image file detected.");
                $path = $this->uploadImageToFirebase($images);
                Image::create([
                    'apartment_id' => $apartment->id,
                    'path' => $path,
                ]);
            } 
            // If `images` is an array of files
            else if (is_array($images)) {
                Log::warning("Multiple image files detected.");
                foreach ($images as $image) {
                    $path = $this->uploadImageToFirebase($image);
                    Image::create([
                        'apartment_id' => $apartment->id,
                        'path' => $path,
                    ]);
                }
            }
        } else {
            Log::warning("No images found in request.");
        }

        Log::warning("I got here ");
        return response()->json($apartment->load('images'), 201);
    } catch (\Exception $e) {
        Log::error('Error storing apartment: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json(['error' => 'Error storing apartment'], 500);
    }
}


    public function update(Request $request, string $id)
    {
        $apartment = Apartment::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'city_id' => 'required',
            'address' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $apartment->update($validated);

            if ($request->hasFile('images')) {
                $apartment->images()->delete(); // Remove existing images

                foreach ($request->file('images') as $image) {
                    $path = $this->uploadImageToFirebase($image);
                    Image::create([
                        'apartment_id' => $apartment->id,
                        'path' => $path,
                    ]);
                }
            }

            return response()->json($apartment->load('images'));
        } catch (\Exception $e) {
            Log::error('Error updating apartment: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating apartment'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $apartment = Apartment::findOrFail($id);
            $apartment->images()->delete(); // Delete associated images
            $apartment->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting apartment: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting apartment'], 500);
        }
    }

    private function uploadImageToFirebase($image)
    {     Log::info('I got to the uploading function');
        try {
            Log::info('Uploading image to Firebase');
    
            $firebaseStorage = Firebase::storage()->getBucket();
            $filePath = 'images/' . time() . '_' . $image->getClientOriginalName();
            $bucket = $firebaseStorage->upload(
                fopen($image->getRealPath(), 'r'),
                [
                    'name' => $filePath,
                ]
            );
    
            $mediaLink = $bucket->info()['mediaLink'];
            Log::info('Image uploaded to Firebase: ' . $mediaLink);
    
            return $mediaLink;
        } catch (\Exception $e) {
            Log::error('Error uploading image to Firebase: ' . $e->getMessage());
            throw $e; // Re-throw the exception to be caught by the caller
        }
    }
}