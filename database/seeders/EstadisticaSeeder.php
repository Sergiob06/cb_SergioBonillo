<?php

namespace Database\Seeders;

use App\Models\Estadistica;
use App\Models\Partido;
use Illuminate\Database\Seeder;

class EstadisticaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('es_ES');

        Estadistica::whereNotNull('partido_id')->delete();

        Partido::with('equipoEstadisticas')
            ->whereHas('equipoEstadisticas', fn ($query) => $query->where('es_local', true))
            ->each(function (Partido $partido) use ($faker) {
            $rebotesDefensivos = $faker->numberBetween(22, 42);
            $rebotesOfensivos = $faker->numberBetween(8, 18);

            Estadistica::create([
                'partido_id' => $partido->id,
                'equipo_id' => $partido->estadisticas_equipo_id,
                'temporada' => '2025/2026',
                'puntos_totales' => (int) ($partido->puntos_anotados ?? 0),
                'rebotes' => $rebotesDefensivos + $rebotesOfensivos,
                'asistencias' => $faker->numberBetween(18, 45),
                'robos' => $faker->numberBetween(6, 18),
                'rebotes_defensivos' => $rebotesDefensivos,
                'rebotes_ofensivos' => $rebotesOfensivos,
                'tapones' => $faker->numberBetween(1, 10),
                'partidos_jugados' => 1,
                'victorias' => ($partido->diferencia_puntos ?? 0) > 0 ? 1 : 0,
                'derrotas' => ($partido->diferencia_puntos ?? 0) < 0 ? 1 : 0,
            ]);
        });
    }
}
