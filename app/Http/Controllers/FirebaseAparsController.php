<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAparsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum')->except(['index']);
    // }

    public function index()
    {
        $apartments = Apartment::with('images')->get(); // Eager load images
        return response()->json($apartments);
    }

    public function store(Request $request)
    {
        Log::warning("Storing apartment");

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'city_id' => 'required',
            'address' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $apartment = Apartment::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $this->uploadImageToFirebase($image);
                Image::create([
                    'apartment_id' => $apartment->id,
                    'path' => $path,
                ]);
            }
        }

        return response()->json($apartment->load('images'), 201);
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
    }

    public function destroy(string $id)
    {
        $apartment = Apartment::findOrFail($id);
        $apartment->images()->delete(); // Delete associated images
        $apartment->delete();
        return response()->json(null, 204);
    }

    private function uploadImageToFirebase($image)
    {
        $firebaseStorage = Firebase::storage()->getBucket();

        $filePath = 'images/' . time() . '_' . $image->getClientOriginalName();
        $bucket = $firebaseStorage->upload(
            fopen($image->getRealPath(), 'r'),
            [
                'name' => $filePath,
            ]
        );

        return $bucket->info()['mediaLink'];
    }
}
