<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Breed;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Species;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $birthDate = $this->faker->dateTimeBetween(
            '-20 years',
            '-1 day'
        );

        $deathDate = $this->faker->boolean(20)
            ? $this->faker->dateTimeBetween(
                $birthDate->format('Y-m-d') . ' +1 day',
                'today'
            )
            : null;
        return [
            "name" => $this->faker->lastName(),
            "gender" =>  $this->faker->randomElement(Gender::cases()),
            'birth_date' => $birthDate->format('Y-m-d'),
            'death_date' => $deathDate?->format('Y-m-d'),
            "client_id" => Client::factory(),
            "species_id" => fn () => Species::query()->inRandomOrder()->value('id'),
            "breed_id" => fn () => Breed::query()->inRandomOrder()->value('id'),
        ];
    }
}
