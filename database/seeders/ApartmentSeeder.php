<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentSeeder extends Seeder
{
    public function run()
    {
        $apartments = [
            [
                'name' => 'Sunny Downtown Loft',
                'description' => 'A beautiful loft apartment in the heart of downtown.',
                'address' => '123 Main St',
                'price' => 1500.00,
                'city_id' => 1
            ],
            [
                'name' => 'Cozy Studio Near Park',
                'description' => 'Compact and comfortable studio apartment close to Central Park.',
                'address' => '456 Park Ave',
                'price' => 1200.50,
                'city_id' => 1
            ],
            [
                'name' => 'Luxury Penthouse',
                'description' => 'Spacious penthouse with panoramic city views.',
                'address' => '789 Sky Tower',
                'price' => 5000.00,
                'city_id' => 2
            ],
            // Add more apartments as needed
        ];

        foreach ($apartments as $apartment) {
            DB::table('apartments')->insert([
                'name' => $apartment['name'],
                'description' => $apartment['description'],
                'address' => $apartment['address'],
                'price' => $apartment['price'],
                'city_id' => $apartment['city_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
