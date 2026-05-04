<?php

namespace Database\Factories;

use App\Models\Clasificacion;
use App\Models\Equipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clasificacion>
 */
class ClasificacionFactory extends Factory
{
    protected $model = Clasificacion::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('es_ES');
        $equipo = Equipo::query()->inRandomOrder()->first() ?? Equipo::factory()->create();
        $jugados = $faker->numberBetween(8, 26);
        $ganados = $faker->numberBetween(0, $jugados);
        $perdidos = $jugados - $ganados;
        $favor = $faker->numberBetween($jugados * 55, $jugados * 90);
        $contra = $faker->numberBetween($jugados * 50, $jugados * 88);

        return [
            'equipo_id' => $equipo->id,
            'equipo_nombre' => $equipo->nombre,
            'categoria' => $equipo->categoria,
            'temporada' => '2025/2026',
            'posicion' => 1,
            'partidos_jugados' => $jugados,
            'partidos_ganados' => $ganados,
            'partidos_perdidos' => $perdidos,
            'puntos_favor' => $favor,
            'puntos_contra' => $contra,
            'puntos' => ($ganados * 2) + $perdidos,
        ];
    }
}
