<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Estadistica;
use Database\Seeders\Support\SeasonSimulationStore;
use Illuminate\Database\Seeder;

class EstadisticaSeeder extends Seeder
{
    public function run(): void
    {
        $simulation = SeasonSimulationStore::getOrBuild(Equipo::query()->get());

        foreach ($simulation['statistics'] as $fila) {
            Estadistica::create([
                'equipo_id' => $fila['equipo_id'],
                'temporada' => $simulation['temporada'],
                'puntos_totales' => $fila['puntos_totales'],
                'rebotes' => $fila['rebotes'],
                'asistencias' => $fila['asistencias'],
                'robos' => $fila['robos'],
                'rebotes_defensivos' => $fila['rebotes_defensivos'],
                'rebotes_ofensivos' => $fila['rebotes_ofensivos'],
                'tapones' => $fila['tapones'],
                'partidos_jugados' => $fila['partidos_jugados'],
                'victorias' => $fila['partidos_ganados'],
                'derrotas' => $fila['partidos_perdidos'],
            ]);
        }
    }
}
