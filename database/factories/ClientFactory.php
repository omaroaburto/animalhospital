<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "first_name" => $this->faker->firstName(),
            "paternal_name" => $this->faker->lastName(),
            "maternal_name"=> $this->faker->lastName(),
           //"password" => "PapasConQueso2020",
            //"email" => $this->faker->unique()->safeEmail(),
            "phone" => $this->faker->unique()->numerify('9########'),
            "rut" => self::randomRut(),
            "street" => $this->faker->streetName(),
            "street_number" => $this->faker->numberBetween(1,1000),
            "apartment_number" => $this->faker->numberBetween(1,1000),
            "city_id" => fn () => City::query()->inRandomOrder()->value('id'),
            "user_id" => User::Factory()
        ];
    }

    private function randomRut(): string
    {
        $rut = random_int(1_000_000, 25_000_000);

        $suma = 0;
        $multiplicador = 2;

        foreach (array_reverse(str_split((string) $rut)) as $digito) {
            $suma += $digito * $multiplicador;
            $multiplicador = $multiplicador < 7 ? $multiplicador + 1 : 2;
        }

        $resto = 11 - ($suma % 11);

        $dv = match ($resto) {
            11 => '0',
            10 => 'K',
            default => (string) $resto,
        };

        return "{$rut}-{$dv}";
    }
}
