<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Image;
use Illuminate\Support\Facades\Log; 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use Illuminate\Support\Facades\Log;

class ApartmentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum')->except(['index', 'unbooked']);
    // }

    public function index()
    {
        $apartments = Apartment::with('images')->get();
        return response()->json($apartments);
    }

    public function store(Request $request)
    {
        Log::warning("Storing apartment");

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'city_id' => 'required|exists:cities,id',
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

        if ($request->hasFile('images')) {
            $apartment->images()->delete();

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
        $apartment->images()->delete();
        $apartment->delete();
        return response()->json(null, 204);
    }

    public function unbooked(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $unbookedApartments = Apartment::unbooked($startDate, $endDate)->get();

        return response()->json($unbookedApartments);
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
