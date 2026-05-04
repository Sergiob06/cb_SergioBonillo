<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Jugador;
use Illuminate\Database\Seeder;

class JugadorSeeder extends Seeder
{
    public function run(): void
    {
        Equipo::query()->each(function (Equipo $equipo) {
            $totalJugadores = fake('es_ES')->numberBetween(8, 12);
            $dorsalesDisponibles = collect(range(0, 99))->shuffle()->take($totalJugadores)->values();
            $baseRotacion = ['Base', 'Escolta', 'Alero', 'Ala-pivot', 'Pivot', 'Base', 'Escolta', 'Alero'];
            $extras = collect(['Ala-pivot', 'Pivot', 'Base', 'Escolta', 'Alero'])
                ->shuffle()
                ->take(max(0, $totalJugadores - count($baseRotacion)))
                ->values();
            $posiciones = collect($baseRotacion)->take($totalJugadores)->concat($extras)->values();

            Jugador::factory()
                ->count($totalJugadores)
                ->sequence(fn ($sequence) => [
                    'equipo_id' => $equipo->id,
                    'dorsal' => $dorsalesDisponibles[$sequence->index],
                    'posicion' => $posiciones[$sequence->index],
                ])
                ->create();

            $equipo->update(['numero_jugadores' => $totalJugadores]);
        });
    }
}
