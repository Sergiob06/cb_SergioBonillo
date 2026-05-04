<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Jugador;
use Carbon\Carbon;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jugador>
 */
class JugadorFactory extends Factory
{
    protected $model = Jugador::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('es_ES');
        $birthDate = $faker->dateTimeBetween('-38 years', '-18 years');

        return [
            'nombre' => $faker->firstName(),
            'apellido' => trim($faker->lastName() . ' ' . $faker->lastName()),
            'posicion' => $faker->randomElement(['Base', 'Escolta', 'Alero', 'Ala-pivot', 'Pivot']),
            'dorsal' => $faker->numberBetween(0, 99),
            'fecha_nacimiento' => Carbon::instance($birthDate)->toDateString(),
            'imagen_jugador' => 'https://placehold.co/600x800/1d3557/ffffff?text=' . urlencode($faker->firstName()),
        ];
    }
}
