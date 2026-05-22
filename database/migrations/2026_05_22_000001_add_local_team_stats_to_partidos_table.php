<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            foreach (['triples', 'tiros_libres', 'rebotes', 'asistencias', 'robos', 'perdidas', 'faltas'] as $column) {
                if (!Schema::hasColumn('partidos', $column)) {
                    $table->unsignedInteger($column)->nullable()->after('puntos_visitante');
                }
            }
        });

        DB::table('partidos')
            ->leftJoin('estadisticas', 'estadisticas.partido_id', '=', 'partidos.id')
            ->where('partidos.estado', 'jugado')
            ->select([
                'partidos.id',
                'partidos.puntos_local',
                'estadisticas.rebotes',
                'estadisticas.asistencias',
                'estadisticas.robos',
            ])
            ->orderBy('partidos.id')
            ->chunkById(100, function ($partidos) {
                foreach ($partidos as $partido) {
                    DB::table('partidos')
                        ->where('id', $partido->id)
                        ->update([
                            'triples' => 5 + ($partido->id % 8),
                            'tiros_libres' => 8 + ($partido->id % 14),
                            'rebotes' => $partido->rebotes ?? 28 + ($partido->id % 18),
                            'asistencias' => $partido->asistencias ?? 10 + ($partido->id % 18),
                            'robos' => $partido->robos ?? 4 + ($partido->id % 10),
                            'perdidas' => 8 + ($partido->id % 12),
                            'faltas' => 12 + ($partido->id % 11),
                        ]);
                }
            }, 'partidos.id', 'id');
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            foreach (['faltas', 'perdidas', 'robos', 'asistencias', 'rebotes', 'tiros_libres', 'triples'] as $column) {
                if (Schema::hasColumn('partidos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
