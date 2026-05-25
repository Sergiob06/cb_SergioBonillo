<?php

namespace Database\Seeders;

use App\Models\Estadistica;
use App\Models\EstadisticaEquipo;
use App\Models\Partido;
use Illuminate\Database\Seeder;

class EstadisticaSeeder extends Seeder
{
    public function run(): void
    {
        EstadisticaEquipo::whereHas('partido', fn ($query) => $query->where('estado', 'proximo'))->delete();
        Estadistica::whereHas('partido', fn ($query) => $query->where('estado', 'proximo'))->delete();

        Partido::with(['equipoLocal', 'equipoVisitante', 'equipoEstadisticas'])
            ->jugados()
            ->each(function (Partido $partido) {
                $estadisticaLocal = $this->crearEstadisticaEquipo($partido, true);
                $estadisticaVisitante = $this->crearEstadisticaEquipo($partido, false);

                $estadisticaBellreguard = collect([$estadisticaLocal, $estadisticaVisitante])
                    ->first(fn (?EstadisticaEquipo $estadistica) => $estadistica?->equipo?->es_local);

                if (! $estadisticaBellreguard) {
                    return;
                }

                $estadisticaBellreguard->load(['equipo', 'partido.estadisticasEquipos']);

                Estadistica::updateOrCreate([
                    'partido_id' => $partido->id,
                ], [
                    'equipo_id' => $estadisticaBellreguard->equipo_id,
                    'temporada' => '2025/2026',
                    'puntos_totales' => (int) ($estadisticaBellreguard->puntos_anotados ?? 0),
                    'rebotes' => (int) ($estadisticaBellreguard->rebotes_totales ?? 0),
                    'asistencias' => (int) ($estadisticaBellreguard->asistencias ?? 0),
                    'robos' => (int) ($estadisticaBellreguard->robos ?? 0),
                    'rebotes_defensivos' => (int) ($estadisticaBellreguard->rebotes_defensivos ?? 0),
                    'rebotes_ofensivos' => (int) ($estadisticaBellreguard->rebotes_ofensivos ?? 0),
                    'tapones' => (int) ($estadisticaBellreguard->tapones ?? 0),
                    'partidos_jugados' => 1,
                    'victorias' => ($estadisticaBellreguard->puntos_anotados ?? 0) > ($estadisticaBellreguard->estadisticaRival()?->puntos_anotados ?? 0) ? 1 : 0,
                    'derrotas' => ($estadisticaBellreguard->puntos_anotados ?? 0) < ($estadisticaBellreguard->estadisticaRival()?->puntos_anotados ?? 0) ? 1 : 0,
                ]);
            });
    }

    private function crearEstadisticaEquipo(Partido $partido, bool $esLocal): ?EstadisticaEquipo
    {
        $equipo = $esLocal ? $partido->equipoLocal : $partido->equipoVisitante;

        if (! $equipo) {
            return null;
        }

        $puntos = $esLocal ? $partido->puntos_local : $partido->puntos_visitante;
        $salt = ($esLocal ? 'local' : 'visitante').'|'.$partido->id;
        $rebotesDefensivos = $this->numberFor('rebotes_defensivos|'.$salt, $partido->id, 18, 42);
        $rebotesOfensivos = $this->numberFor('rebotes_ofensivos|'.$salt, $partido->id, 6, 18);

        $estadistica = EstadisticaEquipo::updateOrCreate([
            'partido_id' => $partido->id,
            'equipo_id' => $equipo->id,
        ], [
            'es_local' => $esLocal,
            'puntos_anotados' => $puntos,
            't2_intentados' => $this->numberFor('t2|'.$salt, $partido->id, 28, 55),
            't3_intentados' => $this->numberFor('t3|'.$salt, $partido->id, 12, 32),
            'tl_intentados' => $this->numberFor('tl|'.$salt, $partido->id, 8, 28),
            'balones_perdidos' => $this->numberFor('perdidas|'.$salt, $partido->id, 7, 20),
            'rebotes_ofensivos' => $rebotesOfensivos,
            'tiros_anotados' => max(1, intdiv((int) $puntos, 2)),
            'rebotes_defensivos' => $rebotesDefensivos,
            'asistencias' => $this->numberFor('asistencias|'.$salt, $partido->id, 8, 26),
            'robos' => $this->numberFor('robos|'.$salt, $partido->id, 3, 14),
            'tapones' => $this->numberFor('tapones|'.$salt, $partido->id, 0, 8),
            'faltas' => $this->numberFor('faltas|'.$salt, $partido->id, 10, 25),
        ]);

        return $estadistica->load(['equipo', 'partido.estadisticasEquipos']);
    }

    private function numberFor(string $salt, int $id, int $min, int $max): int
    {
        return $min + (abs(crc32($salt.'|'.$id)) % ($max - $min + 1));
    }
}
