<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['country_id' => 1, 'name' => 'New York'],
            ['country_id' => 1, 'name' => 'Los Angeles'],
            ['country_id' => 2, 'name' => 'London'],
            ['country_id' => 2, 'name' => 'Manchester'],
            ['country_id' => 3, 'name' => 'Paris'],
            ['country_id' => 3, 'name' => 'Lyon'],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'country_id' => $city['country_id'],
                'name' => $city['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
