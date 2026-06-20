<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = json_decode(
            file_get_contents(database_path('data/regions_cities.json')),
            true
        );

        DB::transaction(function () use ($json) {

            foreach ($json['regions'] as $regionData) {

                $region = Region::firstOrCreate([
                    'name' => $regionData['name'],
                ]);

                foreach ($regionData['communes'] as $communeData) {

                    City::firstOrCreate([
                        'name' => $communeData['name'],
                        'region_id' => $region->id,
                    ]);
                }
            }
        });
    }
}
