<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('partidos', 'estado')) {
            Schema::table('partidos', function (Blueprint $table) {
                $table->string('estado', 20)->default('proximo')->after('fecha_partido');
            });
        }

        DB::table('partidos')
            ->leftJoin('estadisticas', 'estadisticas.partido_id', '=', 'partidos.id')
            ->where(function ($query) {
                $query->whereNotNull('estadisticas.id')
                    ->orWhere(function ($resultQuery) {
                        $resultQuery->whereNotNull('partidos.puntos_local')
                            ->whereNotNull('partidos.puntos_visitante');
                    });
            })
            ->update(['partidos.estado' => 'jugado']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('partidos', 'estado')) {
            Schema::table('partidos', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }
    }
};
