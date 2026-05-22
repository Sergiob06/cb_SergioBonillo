<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $externalTeamIds = DB::table('equipos')
            ->where('es_local', false)
            ->pluck('id');

        if ($externalTeamIds->isNotEmpty()) {
            DB::table('jugadores')
                ->whereIn('equipo_id', $externalTeamIds)
                ->delete();
        }

        DB::table('equipos')
            ->leftJoin('jugadores', 'equipos.id', '=', 'jugadores.equipo_id')
            ->where('equipos.es_local', true)
            ->groupBy('equipos.id')
            ->select('equipos.id', DB::raw('count(jugadores.id) as total'))
            ->orderBy('equipos.id')
            ->chunk(100, function ($equipos) {
                foreach ($equipos as $equipo) {
                    DB::table('equipos')
                        ->where('id', $equipo->id)
                        ->update(['numero_jugadores' => $equipo->total]);
                }
            });

        DB::table('equipos')
            ->where('es_local', false)
            ->update(['numero_jugadores' => 0]);
    }

    public function down(): void
    {
        // Los jugadores eliminados de equipos externos no se restauran para preservar la regla de dominio actual.
    }
};
