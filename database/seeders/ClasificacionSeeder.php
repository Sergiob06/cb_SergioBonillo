<?php

namespace Database\Seeders;

use App\Models\Clasificacion;
use App\Models\Equipo;
use Database\Seeders\Support\SeasonSimulationStore;
use Illuminate\Database\Seeder;

class ClasificacionSeeder extends Seeder
{
    public function run(): void
    {
        $simulation = SeasonSimulationStore::getOrBuild(Equipo::query()->get());

        foreach ($simulation['standings'] as $fila) {
            Clasificacion::factory()->create([
                'equipo_id' => $fila['equipo_id'],
                'equipo_nombre' => $fila['equipo_nombre'],
                'categoria' => $fila['categoria'],
                'temporada' => $simulation['temporada'],
                'posicion' => $fila['posicion'],
                'partidos_jugados' => $fila['partidos_jugados'],
                'partidos_ganados' => $fila['partidos_ganados'],
                'partidos_perdidos' => $fila['partidos_perdidos'],
                'puntos_favor' => $fila['puntos_favor'],
                'puntos_contra' => $fila['puntos_contra'],
                'puntos' => $fila['puntos'],
            ]);
        }
    }
}
