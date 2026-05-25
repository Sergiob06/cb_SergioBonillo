<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadisticas_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partido_id')->constrained('partidos')->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->boolean('es_local')->default(false);
            $table->unsignedInteger('puntos_anotados')->nullable();
            $table->unsignedInteger('t2_intentados')->nullable();
            $table->unsignedInteger('t3_intentados')->nullable();
            $table->unsignedInteger('tl_intentados')->nullable();
            $table->unsignedInteger('balones_perdidos')->nullable();
            $table->unsignedInteger('rebotes_ofensivos')->nullable();
            $table->unsignedInteger('tiros_anotados')->nullable();
            $table->unsignedInteger('rebotes_defensivos')->nullable();
            $table->unsignedInteger('asistencias')->nullable();
            $table->unsignedInteger('robos')->nullable();
            $table->unsignedInteger('tapones')->nullable();
            $table->unsignedInteger('faltas')->nullable();
            $table->timestamps();

            $table->unique(['partido_id', 'equipo_id']);
        });

        DB::table('partidos')
            ->where('estado', 'jugado')
            ->whereNotNull('equipo_local_id')
            ->whereNotNull('equipo_visitante_id')
            ->orderBy('id')
            ->chunkById(100, function ($partidos) {
                foreach ($partidos as $partido) {
                    $this->crearEstadisticaInicial($partido, true);
                    $this->crearEstadisticaInicial($partido, false);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas_equipos');
    }

    private function crearEstadisticaInicial(object $partido, bool $esLocal): void
    {
        $equipoId = $esLocal ? $partido->equipo_local_id : $partido->equipo_visitante_id;

        if (! $equipoId) {
            return;
        }

        $puntos = $esLocal ? $partido->puntos_local : $partido->puntos_visitante;
        $base = (int) ($puntos ?? 0);
        $salt = ($esLocal ? 'local' : 'visitante').'|'.$partido->id;

        DB::table('estadisticas_equipos')->updateOrInsert([
            'partido_id' => $partido->id,
            'equipo_id' => $equipoId,
        ], [
            'es_local' => $esLocal,
            'puntos_anotados' => $puntos,
            't2_intentados' => $this->numberFor($salt.'|t2', 28, 55),
            't3_intentados' => $partido->triples ? max((int) $partido->triples + 4, $this->numberFor($salt.'|t3', 12, 32)) : $this->numberFor($salt.'|t3', 12, 32),
            'tl_intentados' => $partido->tiros_libres ? max((int) $partido->tiros_libres, $this->numberFor($salt.'|tl', 8, 28)) : $this->numberFor($salt.'|tl', 8, 28),
            'balones_perdidos' => $partido->perdidas ?? $this->numberFor($salt.'|bp', 7, 20),
            'rebotes_ofensivos' => $this->numberFor($salt.'|ro', 6, 18),
            'tiros_anotados' => max(1, intdiv($base, 2)),
            'rebotes_defensivos' => $this->numberFor($salt.'|rd', 18, 40),
            'asistencias' => $partido->asistencias ?? $this->numberFor($salt.'|as', 8, 26),
            'robos' => $partido->robos ?? $this->numberFor($salt.'|robs', 3, 14),
            'tapones' => $this->numberFor($salt.'|tap', 0, 8),
            'faltas' => $partido->faltas ?? $this->numberFor($salt.'|faltas', 10, 25),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function numberFor(string $salt, int $min, int $max): int
    {
        return $min + (abs(crc32($salt)) % ($max - $min + 1));
    }
};
