<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Image;
use Illuminate\Support\Facades\Log; 

class ApartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);
    }

    public function index()
    {
        $apartments = Apartment::with('images')->get(); // Eager load images
        return response()->json($apartments);
    }

    public function store(Request $request)
    {   Log::warning("I am here ");
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            // 'city_id' => 'required|exists:cities,id',
            'city_id' => 'required',
            'address' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Validate multiple images
        ]);
        Log::warning("I am here ");
        $apartment = Apartment::create($validated);

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                Image::create([
                    'apartment_id' => $apartment->id,
                    'path' => $path,
                ]);
            }
        }

        return response()->json($apartment->load('images'), 201);
    }

    public function show(string $id)
    {
        $apartment = Apartment::with('images')->findOrFail($id);
        return response()->json($apartment);
    }

    public function update(Request $request, string $id)
    {
        $apartment = Apartment::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $apartment->update($validated);

        if ($request->has('images')) {
            // Remove existing images if necessary
            $apartment->images()->delete();

            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
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
}
