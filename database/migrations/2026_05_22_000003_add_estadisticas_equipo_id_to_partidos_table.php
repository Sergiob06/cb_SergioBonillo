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
            if (!Schema::hasColumn('partidos', 'estadisticas_equipo_id')) {
                $table->foreignId('estadisticas_equipo_id')
                    ->nullable()
                    ->after('equipo_visitante_id')
                    ->constrained('equipos')
                    ->nullOnDelete();
            }
        });

        DB::table('partidos')
            ->join('equipos as local', 'local.id', '=', 'partidos.equipo_local_id')
            ->leftJoin('equipos as visitante', 'visitante.id', '=', 'partidos.equipo_visitante_id')
            ->whereNull('partidos.estadisticas_equipo_id')
            ->where('local.es_local', true)
            ->update(['partidos.estadisticas_equipo_id' => DB::raw('partidos.equipo_local_id')]);

        DB::table('partidos')
            ->leftJoin('equipos as local', 'local.id', '=', 'partidos.equipo_local_id')
            ->join('equipos as visitante', 'visitante.id', '=', 'partidos.equipo_visitante_id')
            ->whereNull('partidos.estadisticas_equipo_id')
            ->where('visitante.es_local', true)
            ->update(['partidos.estadisticas_equipo_id' => DB::raw('partidos.equipo_visitante_id')]);

        DB::table('partidos')
            ->leftJoin('equipos as equipo_stats', 'equipo_stats.id', '=', 'partidos.estadisticas_equipo_id')
            ->where(function ($query) {
                $query->whereNull('partidos.estadisticas_equipo_id')
                    ->orWhere('equipo_stats.es_local', false);
            })
            ->update([
                'triples' => null,
                'tiros_libres' => null,
                'rebotes' => null,
                'asistencias' => null,
                'robos' => null,
                'perdidas' => null,
                'faltas' => null,
            ]);
    }

    public function down(): void
    {
        Schema::table('partidos', function (Blueprint $table) {
            if (Schema::hasColumn('partidos', 'estadisticas_equipo_id')) {
                $table->dropConstrainedForeignId('estadisticas_equipo_id');
            }
        });
    }
};
