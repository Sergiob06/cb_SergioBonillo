<?php

namespace Database\Factories;

use App\Models\Equipo;
use App\Models\Partido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partido>
 */
class PartidoFactory extends Factory
{
    protected $model = Partido::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('es_ES');
        $equipos = Equipo::query()->inRandomOrder()->limit(2)->get();

        if ($equipos->count() < 2) {
            $equipos = collect([
                Equipo::factory()->create(),
                Equipo::factory()->create(),
            ]);
        }

        [$equipoLocal, $equipoVisitante] = $equipos->values()->all();

        if ($equipoLocal->is($equipoVisitante)) {
            $equipoVisitante = Equipo::query()
                ->whereKeyNot($equipoLocal->id)
                ->inRandomOrder()
                ->first() ?? Equipo::factory()->create();
        }

        return [
            'equipo_local_id' => $equipoLocal->id,
            'equipo_visitante_id' => $equipoVisitante->id,
            'estadisticas_equipo_id' => $equipoLocal->es_local
                ? $equipoLocal->id
                : ($equipoVisitante->es_local ? $equipoVisitante->id : null),
            'category_id' => $equipoLocal->es_local
                ? $equipoLocal->category_id
                : ($equipoVisitante->es_local ? $equipoVisitante->category_id : $equipoLocal->category_id),
            'equipo_local' => $equipoLocal->nombre,
            'equipo_visitante' => $equipoVisitante->nombre,
            'fecha_partido' => $faker->dateTimeBetween('-2 months', '+4 months'),
            'lugar' => $faker->randomElement([
                'Pabellon Municipal de Bellreguard',
                'Pabellon Deportivo de Gandia',
                'Polideportivo de Oliva',
                'Pabellon La Safor',
                'Centre Esportiu Municipal',
            ]),
        ];
    }
}
