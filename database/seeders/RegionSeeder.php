<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            'North America',
            'South America',
            'Europe',
            'Africa',
            'Asia',
            'Oceania',
            'Antarctica'
        ];

        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name' => $region,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
