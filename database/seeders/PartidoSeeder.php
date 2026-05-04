<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Partido;
use Database\Seeders\Support\SeasonSimulationStore;
use Illuminate\Database\Seeder;

class PartidoSeeder extends Seeder
{
    public function run(): void
    {
        $equipos = Equipo::query()->get();

        if ($equipos->count() < 2) {
            return;
        }

        $simulation = SeasonSimulationStore::getOrBuild($equipos);

        foreach ($simulation['fixtures'] as $fixture) {
            Partido::create([
                'equipo_local_id' => $fixture['equipo_local_id'],
                'equipo_visitante_id' => $fixture['equipo_visitante_id'],
                'equipo_local' => $fixture['equipo_local'],
                'equipo_visitante' => $fixture['equipo_visitante'],
                'fecha_partido' => $fixture['fecha_partido'],
                'lugar' => $fixture['lugar'],
            ]);
        }
    }
}
