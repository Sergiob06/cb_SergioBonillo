<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Partido;
use Carbon\CarbonImmutable;
use Database\Seeders\Support\LocalLeagueCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PartidoSeeder extends Seeder
{
    public function run(): void
    {
        $equipos = Equipo::query()->get();

        if ($equipos->count() < 2) {
            return;
        }

        $fechaBase = CarbonImmutable::create(2026, 1, 10, 10, 0);
        $slot = 0;

        $equipos
            ->groupBy('categoria')
            ->each(function (Collection $equiposCategoria) use (&$slot, $fechaBase) {
                $bellreguard = $equiposCategoria
                    ->filter(fn (Equipo $equipo) => $equipo->es_local)
                    ->values();
                $rivales = $equiposCategoria
                    ->reject(fn (Equipo $equipo) => $equipo->es_local)
                    ->values();

                if ($bellreguard->isEmpty() || $rivales->isEmpty()) {
                    return;
                }

                foreach ($bellreguard as $indiceBellreguard => $equipoBellreguard) {
                    foreach ($rivales as $indiceRival => $rival) {
                        $fechaIda = $fechaBase
                            ->addDays($slot * 4)
                            ->setTime(...$this->horaPorSlot($slot));
                        $slot++;

                        $fechaVuelta = $fechaBase
                            ->addDays($slot * 4)
                            ->setTime(...$this->horaPorSlot($slot));
                        $slot++;

                        $bellreguardEmpiezaEnCasa = ($indiceBellreguard + $indiceRival) % 2 === 0;

                        if ($bellreguardEmpiezaEnCasa) {
                            $this->crearPartido($equipoBellreguard, $rival, $equipoBellreguard, $fechaIda);
                            $this->crearPartido($rival, $equipoBellreguard, $equipoBellreguard, $fechaVuelta);
                        } else {
                            $this->crearPartido($rival, $equipoBellreguard, $equipoBellreguard, $fechaIda);
                            $this->crearPartido($equipoBellreguard, $rival, $equipoBellreguard, $fechaVuelta);
                        }
                    }
                }

                if ($bellreguard->count() > 1) {
                    for ($i = 0; $i < $bellreguard->count(); $i++) {
                        for ($j = $i + 1; $j < $bellreguard->count(); $j++) {
                            $fechaDerbiIda = $fechaBase
                                ->addDays($slot * 4)
                                ->setTime(...$this->horaPorSlot($slot));
                            $slot++;

                            $fechaDerbiVuelta = $fechaBase
                                ->addDays($slot * 4)
                                ->setTime(...$this->horaPorSlot($slot));
                            $slot++;

                            $this->crearPartido($bellreguard[$i], $bellreguard[$j], $bellreguard[$i], $fechaDerbiIda);
                            $this->crearPartido($bellreguard[$j], $bellreguard[$i], $bellreguard[$j], $fechaDerbiVuelta);
                        }
                    }
                }
            });

        $this->crearProximosDeEjemplo($equipos);
    }

    private function crearPartido(Equipo $local, Equipo $visitante, Equipo $equipoEstadisticas, CarbonImmutable $fecha): Partido
    {
        $esProximo = $fecha->greaterThan(CarbonImmutable::create(2026, 5, 25, 23, 59));
        [$puntosLocal, $puntosVisitante] = $esProximo ? [null, null] : $this->resultadoRealista($local, $visitante);

        return Partido::updateOrCreate([
            'equipo_local_id' => $local->id,
            'equipo_visitante_id' => $visitante->id,
            'fecha_partido' => $fecha,
        ], [
            'equipo_local_id' => $local->id,
            'equipo_visitante_id' => $visitante->id,
            'estadisticas_equipo_id' => $equipoEstadisticas?->id,
            'category_id' => $equipoEstadisticas?->category_id ?? $local->category_id,
            'equipo_local' => $local->nombre,
            'equipo_visitante' => $visitante->nombre,
            'fecha_partido' => $fecha,
            'estado' => $esProximo ? 'proximo' : 'jugado',
            'lugar' => LocalLeagueCatalog::venueForTeam($local->nombre),
            'puntos_local' => $puntosLocal,
            'puntos_visitante' => $puntosVisitante,
        ] + ($esProximo ? $this->estadisticasVacias() : $this->estadisticasDeEjemplo($puntosLocal, $puntosVisitante)));
    }

    private function crearProximosDeEjemplo(Collection $equipos): void
    {
        $bellreguard = $equipos->first(fn (Equipo $equipo) => $equipo->es_local);

        if (! $bellreguard) {
            return;
        }

        $rival = $equipos->first(fn (Equipo $equipo) => ! $equipo->es_local && $equipo->category_id === $bellreguard->category_id)
            ?? $equipos->first(fn (Equipo $equipo) => ! $equipo->es_local);

        if (! $rival) {
            return;
        }

        $this->crearPartidoProximo($bellreguard, $rival, $bellreguard, CarbonImmutable::create(2026, 6, 6, 18, 30));
        $this->crearPartidoProximo($rival, $bellreguard, $bellreguard, CarbonImmutable::create(2026, 6, 13, 18, 30));
    }

    private function crearPartidoProximo(Equipo $local, Equipo $visitante, Equipo $equipoEstadisticas, CarbonImmutable $fecha): Partido
    {
        return Partido::updateOrCreate([
            'equipo_local_id' => $local->id,
            'equipo_visitante_id' => $visitante->id,
            'fecha_partido' => $fecha,
        ], [
            'equipo_local_id' => $local->id,
            'equipo_visitante_id' => $visitante->id,
            'estadisticas_equipo_id' => $equipoEstadisticas->id,
            'category_id' => $equipoEstadisticas->category_id ?? $local->category_id,
            'equipo_local' => $local->nombre,
            'equipo_visitante' => $visitante->nombre,
            'fecha_partido' => $fecha,
            'estado' => 'proximo',
            'lugar' => LocalLeagueCatalog::venueForTeam($local->nombre),
            'puntos_local' => null,
            'puntos_visitante' => null,
        ] + $this->estadisticasVacias());
    }

    /**
     * @return array{0:int,1:int}
     */
    private function resultadoRealista(Equipo $local, Equipo $visitante): array
    {
        $baseLocal = 62 + ($local->es_local ? 5 : 0) + $this->numberFor('local', $local->id, $visitante->id, -8, 18);
        $baseVisitante = 58 + ($visitante->es_local ? 5 : 0) + $this->numberFor('visitante', $local->id, $visitante->id, -10, 16);

        $puntosLocal = max(42, $baseLocal);
        $puntosVisitante = max(38, $baseVisitante);

        if ($puntosLocal === $puntosVisitante) {
            $puntosLocal += $this->numberFor('desempate', $local->id, $visitante->id, 0, 1) === 1 ? 2 : 0;
            $puntosVisitante += $puntosLocal === $puntosVisitante ? 2 : 0;
        }

        return [$puntosLocal, $puntosVisitante];
    }

    /**
     * @return array{0:int,1:int}
     */
    private function horaPorSlot(int $slot): array
    {
        return match ($slot % 5) {
            0 => [10, 0],
            1 => [12, 30],
            2 => [17, 0],
            3 => [18, 30],
            default => [20, 0],
        };
    }

    /**
     * @return array<string, int>
     */
    private function estadisticasDeEjemplo(int $puntosLocal, int $puntosVisitante): array
    {
        $puntosReferencia = max($puntosLocal, $puntosVisitante);

        return [
            'triples' => max(2, min(16, intdiv($puntosReferencia, 9) + $this->numberFor('triples', $puntosLocal, $puntosVisitante, -2, 3))),
            'tiros_libres' => max(4, min(30, intdiv($puntosReferencia, 5) + $this->numberFor('tl', $puntosLocal, $puntosVisitante, -3, 5))),
            'rebotes' => $this->numberFor('rebotes', $puntosLocal, $puntosVisitante, 24, 48),
            'asistencias' => $this->numberFor('asistencias', $puntosLocal, $puntosVisitante, 8, 26),
            'robos' => $this->numberFor('robos', $puntosLocal, $puntosVisitante, 3, 14),
            'perdidas' => $this->numberFor('perdidas', $puntosLocal, $puntosVisitante, 6, 18),
            'faltas' => $this->numberFor('faltas', $puntosLocal, $puntosVisitante, 10, 25),
        ];
    }

    /**
     * @return array<string, null>
     */
    private function estadisticasVacias(): array
    {
        return [
            'triples' => null,
            'tiros_libres' => null,
            'rebotes' => null,
            'asistencias' => null,
            'robos' => null,
            'perdidas' => null,
            'faltas' => null,
        ];
    }

    private function numberFor(string $salt, int $a, int $b, int $min, int $max): int
    {
        return $min + (abs(crc32($salt.'|'.$a.'|'.$b)) % ($max - $min + 1));
    }
}
