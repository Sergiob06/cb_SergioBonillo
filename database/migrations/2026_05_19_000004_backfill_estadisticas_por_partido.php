<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('partidos')
            ->select('id', 'puntos_local', 'puntos_visitante')
            ->orderBy('id')
            ->chunkById(100, function ($partidos) use ($now) {
                foreach ($partidos as $partido) {
                    $exists = DB::table('estadisticas')
                        ->where('partido_id', $partido->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $puntosLocal = (int) ($partido->puntos_local ?? 0);
                    $puntosVisitante = (int) ($partido->puntos_visitante ?? 0);
                    $puntosTotales = $puntosLocal + $puntosVisitante;
                    $rebotesDefensivos = 24 + ($partido->id % 15);
                    $rebotesOfensivos = 8 + ($partido->id % 8);

                    DB::table('estadisticas')->insert([
                        'partido_id' => $partido->id,
                        'puntos_totales' => $puntosTotales,
                        'rebotes' => $rebotesDefensivos + $rebotesOfensivos,
                        'asistencias' => 16 + ($partido->id % 24),
                        'robos' => 5 + ($partido->id % 10),
                        'rebotes_defensivos' => $rebotesDefensivos,
                        'rebotes_ofensivos' => $rebotesOfensivos,
                        'tapones' => 1 + ($partido->id % 7),
                        'partidos_jugados' => 0,
                        'victorias' => 0,
                        'derrotas' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        DB::table('estadisticas')
            ->whereNotNull('partido_id')
            ->delete();
    }
};
