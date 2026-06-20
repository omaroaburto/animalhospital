<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Species;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpeciesBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $json = json_decode(
            file_get_contents(
                database_path('data/species_breeds.json')
            ),
            true
        );

        DB::transaction(function () use ($json) {

            foreach ($json['species'] as $speciesData) {

                $species = Species::firstOrCreate([
                    'name' => $speciesData['name'],
                ]);

                foreach ($speciesData['breeds'] as $breedName) {

                    Breed::firstOrCreate([
                        'name' => $breedName,
                        'species_id' => $species->id,
                    ]);
                }
            }
        });
    }
}
