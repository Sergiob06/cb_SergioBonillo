<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Jugador;
use App\Models\Posicion;
use Carbon\Carbon;
use Database\Seeders\Support\PublicImageCatalog;


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
        $posicion = Posicion::query()->inRandomOrder()->first();
        $nombrePosicion = $posicion?->nombre ?? $faker->randomElement(['Base', 'Escolta', 'Alero', 'Ala-pívot', 'Pívot']);

        return [
            'nombre' => $faker->firstName(),
            'apellido' => trim($faker->lastName() . ' ' . $faker->lastName()),
            'posicion_id' => $posicion?->id,
            'posicion' => $nombrePosicion,
            'dorsal' => $faker->numberBetween(0, 99),
            'fecha_nacimiento' => Carbon::instance($birthDate)->toDateString(),
            'imagen_jugador' => PublicImageCatalog::playerImageFor($faker->name()),
        ];
    }
}
