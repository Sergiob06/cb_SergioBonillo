<?php

namespace Database\Seeders\Support;

use App\Models\Equipo;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class SeasonSimulationBuilder
{
    /**
     * @param Collection<int, Equipo> $equipos
     * @return array{temporada:string,fixtures:Collection<int, array<string, mixed>>,standings:Collection<int, array<string, mixed>>,statistics:Collection<int, array<string, mixed>>}
     */
    public static function build(Collection $equipos, string $temporada = '2025/2026'): array
    {
        $faker = fake('es_ES');
        $strengths = [];
        $teamStats = [];

        foreach ($equipos as $equipo) {
            $strengths[$equipo->id] = $faker->numberBetween(68, 92) + ($equipo->es_local ? 3 : 0);
            $teamStats[$equipo->id] = [
                'equipo_id' => $equipo->id,
                'equipo_nombre' => $equipo->nombre,
                'categoria' => $equipo->categoria,
                'temporada' => $temporada,
                'partidos_jugados' => 0,
                'partidos_ganados' => 0,
                'partidos_perdidos' => 0,
                'puntos_favor' => 0,
                'puntos_contra' => 0,
                'puntos' => 0,
                'puntos_totales' => 0,
                'rebotes' => 0,
                'asistencias' => 0,
                'robos' => 0,
                'rebotes_defensivos' => 0,
                'rebotes_ofensivos' => 0,
                'tapones' => 0,
            ];
        }

        $fixtures = collect();
        $kickoff = CarbonImmutable::now()->subMonths(4)->setTime(10, 0);
        $slot = 0;

        $equipos
            ->groupBy('categoria')
            ->each(function (Collection $equiposCategoria) use (&$fixtures, &$teamStats, $strengths, &$slot, $kickoff, $faker) {
                $lista = $equiposCategoria->values();

                for ($i = 0; $i < $lista->count(); $i++) {
                    for ($j = $i + 1; $j < $lista->count(); $j++) {
                        $partidos = [
                            [$lista[$i], $lista[$j]],
                            [$lista[$j], $lista[$i]],
                        ];

                        foreach ($partidos as [$local, $visitante]) {
                            $fecha = $kickoff
                                ->addDays($slot * 3)
                                ->setTime($faker->randomElement([10, 12, 17, 19, 20]), $faker->randomElement([0, 30]));
                            $slot++;

                            $puntosLocal = max(52, (int) round($strengths[$local->id] + $faker->numberBetween(-8, 18)));
                            $puntosVisitante = max(48, (int) round($strengths[$visitante->id] + $faker->numberBetween(-10, 14)));

                            if ($puntosLocal === $puntosVisitante) {
                                $puntosLocal += $faker->boolean() ? $faker->numberBetween(2, 8) : 0;
                                $puntosVisitante += $puntosLocal > $puntosVisitante ? 0 : $faker->numberBetween(2, 8);
                            }

                            $ganadorLocal = $puntosLocal > $puntosVisitante;

                            $fixtures->push([
                                'equipo_local_id' => $local->id,
                                'equipo_visitante_id' => $visitante->id,
                                'equipo_local' => $local->nombre,
                                'equipo_visitante' => $visitante->nombre,
                                'fecha_partido' => $fecha,
                                'lugar' => LocalLeagueCatalog::venueForTeam($local->nombre),
                                'puntos_local' => $puntosLocal,
                                'puntos_visitante' => $puntosVisitante,
                            ]);

                            self::accumulate($teamStats[$local->id], $puntosLocal, $puntosVisitante, $ganadorLocal, $faker);
                            self::accumulate($teamStats[$visitante->id], $puntosVisitante, $puntosLocal, !$ganadorLocal, $faker);
                        }
                    }
                }
            });

        $standings = collect($teamStats)
            ->groupBy('categoria')
            ->flatMap(function (Collection $statsCategoria) {
                $ordenado = $statsCategoria->sort(function (array $a, array $b) {
                    return [$b['puntos'], $b['partidos_ganados'], $b['puntos_favor'] - $b['puntos_contra'], $b['puntos_favor']]
                        <=> [$a['puntos'], $a['partidos_ganados'], $a['puntos_favor'] - $a['puntos_contra'], $a['puntos_favor']];
                });

                return $ordenado
                    ->values()
                    ->map(function (array $fila, int $index) {
                        $fila['posicion'] = $index + 1;

                        return $fila;
                    });
            })
            ->values();

        return [
            'temporada' => $temporada,
            'fixtures' => $fixtures,
            'standings' => $standings,
            'statistics' => collect($teamStats)->values(),
        ];
    }

    /**
     * @param array<string, mixed> $teamRow
     */
    private static function accumulate(array &$teamRow, int $favor, int $contra, bool $victoria, $faker): void
    {
        $teamRow['partidos_jugados']++;
        $teamRow['partidos_ganados'] += $victoria ? 1 : 0;
        $teamRow['partidos_perdidos'] += $victoria ? 0 : 1;
        $teamRow['puntos_favor'] += $favor;
        $teamRow['puntos_contra'] += $contra;
        $teamRow['puntos'] += $victoria ? 2 : 1;
        $teamRow['puntos_totales'] += $favor;

        $rebotesDef = $faker->numberBetween(18, 31);
        $rebotesOf = $faker->numberBetween(7, 16);

        $teamRow['rebotes_defensivos'] += $rebotesDef;
        $teamRow['rebotes_ofensivos'] += $rebotesOf;
        $teamRow['rebotes'] += $rebotesDef + $rebotesOf;
        $teamRow['asistencias'] += $faker->numberBetween(10, 24);
        $teamRow['robos'] += $faker->numberBetween(4, 13);
        $teamRow['tapones'] += $faker->numberBetween(1, 7);
    }
}
