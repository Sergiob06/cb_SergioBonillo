<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('posiciones')) {
            Schema::create('posiciones', function (Blueprint $table) {
                $table->id();
                $table->string('nombre')->unique();
                $table->timestamps();
            });
        }

        $this->seedPosiciones();

        if (Schema::hasTable('jugadores') && !Schema::hasColumn('jugadores', 'posicion_id')) {
            Schema::table('jugadores', function (Blueprint $table) {
                $table->foreignId('posicion_id')
                    ->nullable()
                    ->after('posicion')
                    ->constrained('posiciones')
                    ->nullOnDelete();
            });
        }

        $this->backfillJugadores();
    }

    public function down(): void
    {
        if (Schema::hasTable('jugadores') && Schema::hasColumn('jugadores', 'posicion_id')) {
            Schema::table('jugadores', function (Blueprint $table) {
                $table->dropConstrainedForeignId('posicion_id');
            });
        }

        Schema::dropIfExists('posiciones');
    }

    private function seedPosiciones(): void
    {
        $now = now();

        foreach (['Base', 'Escolta', 'Alero', 'Ala-pívot', 'Pívot'] as $nombre) {
            DB::table('posiciones')->updateOrInsert(
                ['nombre' => $nombre],
                ['updated_at' => $now, 'created_at' => $now]
            );
        }

        if (!Schema::hasColumn('jugadores', 'posicion')) {
            return;
        }

        DB::table('jugadores')
            ->whereNotNull('posicion')
            ->where('posicion', '!=', '')
            ->select('posicion')
            ->distinct()
            ->orderBy('posicion')
            ->pluck('posicion')
            ->each(function (string $posicion) use ($now) {
                DB::table('posiciones')->updateOrInsert(
                    ['nombre' => $this->normalizarNombrePosicion($posicion)],
                    ['updated_at' => $now, 'created_at' => $now]
                );
            });
    }

    private function backfillJugadores(): void
    {
        if (!Schema::hasColumn('jugadores', 'posicion_id') || !Schema::hasColumn('jugadores', 'posicion')) {
            return;
        }

        $posiciones = DB::table('posiciones')->pluck('id', 'nombre');

        DB::table('jugadores')
            ->whereNull('posicion_id')
            ->whereNotNull('posicion')
            ->where('posicion', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($jugadores) use ($posiciones) {
                foreach ($jugadores as $jugador) {
                    $nombre = $this->normalizarNombrePosicion($jugador->posicion);
                    $posicionId = $posiciones[$nombre] ?? null;

                    if ($posicionId) {
                        DB::table('jugadores')
                            ->where('id', $jugador->id)
                            ->update([
                                'posicion_id' => $posicionId,
                                'posicion' => $nombre,
                            ]);
                    }
                }
            });
    }

    private function normalizarNombrePosicion(string $posicion): string
    {
        $nombre = trim($posicion);
        $clave = mb_strtolower(str_replace(['_', ' '], '-', $nombre));

        return match ($clave) {
            'ala-pivot', 'ala-pívot' => 'Ala-pívot',
            'pivot', 'pívot' => 'Pívot',
            'base' => 'Base',
            'escolta' => 'Escolta',
            'alero' => 'Alero',
            default => $nombre,
        };
    }
};
