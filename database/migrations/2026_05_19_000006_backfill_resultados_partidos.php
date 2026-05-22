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
            ->whereNull('puntos_local')
            ->orWhereNull('puntos_visitante')
            ->orderBy('id')
            ->chunkById(100, function ($partidos) use ($now) {
                foreach ($partidos as $partido) {
                    $puntosLocal = 58 + (($partido->id * 7) % 38);
                    $puntosVisitante = 54 + (($partido->id * 5) % 36);

                    if ($puntosLocal === $puntosVisitante) {
                        $puntosLocal += 3;
                    }

                    DB::table('partidos')
                        ->where('id', $partido->id)
                        ->update([
                            'puntos_local' => $puntosLocal,
                            'puntos_visitante' => $puntosVisitante,
                            'updated_at' => $now,
                        ]);

                    DB::table('estadisticas')
                        ->where('partido_id', $partido->id)
                        ->update([
                            'puntos_totales' => $puntosLocal + $puntosVisitante,
                            'updated_at' => $now,
                        ]);
                }
            });
    }

    public function down(): void
    {
        // No revertimos resultados porque pueden haber sido revisados desde el panel admin.
    }
};
