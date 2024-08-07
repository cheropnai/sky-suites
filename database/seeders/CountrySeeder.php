<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'United States', 'region_id' => 1],
            ['name' => 'Canada', 'region_id' => 1],
            ['name' => 'United Kingdom', 'region_id' => 3],
            ['name' => 'France', 'region_id' => 3],
            ['name' => 'South Africa', 'region_id' => 4],
            ['name' => 'Japan', 'region_id' => 5],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country['name'],
                'region_id' => $country['region_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
