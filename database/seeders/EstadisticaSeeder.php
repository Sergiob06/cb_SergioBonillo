<?php

namespace Database\Seeders;

use App\Models\Estadistica;
use App\Models\Partido;
use Illuminate\Database\Seeder;

class EstadisticaSeeder extends Seeder
{
    public function run(): void
    {
        Partido::with('equipoEstadisticas')
            ->whereHas('equipoEstadisticas', fn ($query) => $query->where('es_local', true))
            ->each(function (Partido $partido) {
                $rebotesDefensivos = $this->numberFor('rebotes_defensivos', $partido->id, 22, 42);
                $rebotesOfensivos = $this->numberFor('rebotes_ofensivos', $partido->id, 8, 18);

                Estadistica::updateOrCreate([
                    'partido_id' => $partido->id,
                ], [
                    'equipo_id' => $partido->estadisticas_equipo_id,
                    'temporada' => '2025/2026',
                    'puntos_totales' => (int) ($partido->puntos_anotados ?? 0),
                    'rebotes' => $rebotesDefensivos + $rebotesOfensivos,
                    'asistencias' => $this->numberFor('asistencias', $partido->id, 18, 45),
                    'robos' => $this->numberFor('robos', $partido->id, 6, 18),
                    'rebotes_defensivos' => $rebotesDefensivos,
                    'rebotes_ofensivos' => $rebotesOfensivos,
                    'tapones' => $this->numberFor('tapones', $partido->id, 1, 10),
                    'partidos_jugados' => 1,
                    'victorias' => ($partido->diferencia_puntos ?? 0) > 0 ? 1 : 0,
                    'derrotas' => ($partido->diferencia_puntos ?? 0) < 0 ? 1 : 0,
                ]);
            });
    }

    private function numberFor(string $salt, int $id, int $min, int $max): int
    {
        return $min + (abs(crc32($salt.'|'.$id)) % ($max - $min + 1));
    }
}
