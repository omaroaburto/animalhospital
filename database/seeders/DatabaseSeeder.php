<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RegionCitySeeder::class,
            SpeciesBreedSeeder::class
        ]);

        Client::factory()
            ->count(20)
            ->hasPets(10)
            ->create();
            
        User::factory()
            ->admin()
            ->count(3)
            ->create();
    }
}
