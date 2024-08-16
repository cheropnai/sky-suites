<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use Illuminate\Support\Facades\Storage;

class ApartmentSeeder extends Seeder
{
    public function run()
    {
        // Make sure the 'images' directory exists in storage/app/public
        Storage::disk('public')->makeDirectory('images');

        // Create a sample apartment
        $apartment = Apartment::create([
            'name' => 'Sample Apartment',
            'description' => 'A beautifully furnished apartment.',
            'price' => 1200,
            'city_id' => 1, // Ensure this ID exists in the cities table
            'address' => '123 Sample Street',
        ]);

        // Simulate image uploads
        $images = ['image1.jpg', 'image2.jpg']; // Place these files in storage/app/public/images

        foreach ($images as $imageName) {
            Storage::disk('public')->putFileAs(
                'images',
                storage_path("app/seeders/$imageName"),
                $imageName
            );
            $apartment->images()->create([
                'path' => "images/$imageName",
            ]);
        }
    }
}


