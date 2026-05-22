<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Posicion;
use Database\Seeders\Support\PublicImageCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JugadorSeeder extends Seeder
{
    /** @var array<int, string> */
    private array $maleImages = [
        'jugadores/MALEDON_EQUIPO_CARITA_380x50_OK.webp',
        'jugadores/RS835803_2526-BLB-JUSTIN-JAWORSKI-1_lpr-600x800.jpg',
        'jugadores/YURTSEVEN_380x501.webp',
        'jugadores/images.jpeg',
        'jugadores/images (1).jpeg',
        'jugadores/images (2).jpeg',
        'jugadores/images (3).jpeg',
        'jugadores/images (4).jpeg',
        'jugadores/images (5).jpeg',
    ];

    /** @var array<int, string> */
    private array $femaleImages = [
        'jugadores/descarga.jpeg',
        'jugadores/descarga (1).jpeg',
        'jugadores/descarga (2).jpeg',
        'jugadores/descarga (3).jpeg',
    ];

    /** @var array<int, array{nombre:string, apellido:string}> */
    private array $maleRoster = [
        ['nombre' => 'Marc', 'apellido' => 'Soler Ferrer'],
        ['nombre' => 'Pau', 'apellido' => 'Vidal Torres'],
        ['nombre' => 'Hugo', 'apellido' => 'Marti Serra'],
        ['nombre' => 'Joan', 'apellido' => 'Navarro Gil'],
        ['nombre' => 'Bruno', 'apellido' => 'Aleman Trejo'],
        ['nombre' => 'David', 'apellido' => 'Almonte Perez'],
        ['nombre' => 'Alex', 'apellido' => 'Garcia Pastor'],
        ['nombre' => 'Sergi', 'apellido' => 'Ferrer Costa'],
        ['nombre' => 'Nico', 'apellido' => 'Molina Ribes'],
        ['nombre' => 'Iker', 'apellido' => 'Campos Ruiz'],
        ['nombre' => 'Arnau', 'apellido' => 'Fuster Mora'],
        ['nombre' => 'Jordi', 'apellido' => 'Pons Sanchis'],
        ['nombre' => 'Adrian', 'apellido' => 'Lopez Cano'],
        ['nombre' => 'Mateo', 'apellido' => 'Romero Orts'],
        ['nombre' => 'Leo', 'apellido' => 'Beltran Ferrando'],
    ];

    /** @var array<int, array{nombre:string, apellido:string}> */
    private array $femaleRoster = [
        ['nombre' => 'Paula', 'apellido' => 'Soler Ferrer'],
        ['nombre' => 'Marta', 'apellido' => 'Vidal Torres'],
        ['nombre' => 'Laia', 'apellido' => 'Marti Serra'],
        ['nombre' => 'Claudia', 'apellido' => 'Navarro Gil'],
        ['nombre' => 'Nuria', 'apellido' => 'Garcia Pastor'],
        ['nombre' => 'Aina', 'apellido' => 'Ferrer Costa'],
        ['nombre' => 'Lucia', 'apellido' => 'Molina Ribes'],
        ['nombre' => 'Carla', 'apellido' => 'Campos Ruiz'],
        ['nombre' => 'Irene', 'apellido' => 'Fuster Mora'],
        ['nombre' => 'Sara', 'apellido' => 'Pons Sanchis'],
        ['nombre' => 'Emma', 'apellido' => 'Lopez Cano'],
        ['nombre' => 'Marina', 'apellido' => 'Romero Orts'],
    ];

    /** @var array<int, string> */
    private array $positions = ['Base', 'Escolta', 'Alero', 'Ala-pívot', 'Pívot', 'Base', 'Escolta', 'Alero', 'Ala-pívot', 'Pívot'];

    /** @var array<int, int> */
    private array $dorsals = [4, 5, 7, 8, 9, 11, 12, 15, 23, 33];

    public function run(): void
    {
        $posicionesPorNombre = collect($this->positions)
            ->unique()
            ->mapWithKeys(fn (string $nombre) => [
                $nombre => Posicion::updateOrCreate(['nombre' => $nombre]),
            ]);

        $equiposLocales = Equipo::locales()->orderBy('id')->get();

        $equiposLocales->values()->each(function (Equipo $equipo, int $teamIndex) use ($posicionesPorNombre) {
            $esFemenino = $this->teamIsFemale($equipo);
            $roster = $esFemenino ? $this->femaleRoster : $this->maleRoster;
            $images = $esFemenino ? $this->femaleImages : $this->maleImages;
            $offset = $teamIndex * 3;

            foreach (range(0, count($this->dorsals) - 1) as $index) {
                $player = $roster[($offset + $index) % count($roster)];
                $position = $this->positions[$index];

                Jugador::updateOrCreate([
                    'nombre' => $player['nombre'],
                    'apellido' => $player['apellido'],
                    'equipo_id' => $equipo->id,
                ], [
                    'dorsal' => $this->dorsals[$index],
                    'posicion_id' => $posicionesPorNombre[$position]->id,
                    'posicion' => $position,
                    'fecha_nacimiento' => $this->birthDateFor($equipo, $index),
                    'imagen_jugador' => $this->imageFor($images, $offset + $index),
                ]);
            }

            $equipo->update(['numero_jugadores' => $equipo->jugadores()->count()]);
        });
    }

    private function teamIsFemale(Equipo $equipo): bool
    {
        return Str::contains(Str::lower($equipo->categoria.' '.$equipo->nombre), ['femenino', 'femeni']);
    }

    private function imageFor(array $images, int $index): string
    {
        $image = $images[$index % count($images)];

        return is_file(public_path($image)) ? $image : PublicImageCatalog::PLAYER_FALLBACK;
    }

    private function birthDateFor(Equipo $equipo, int $index): string
    {
        $category = Str::lower($equipo->categoria ?? '');

        $year = match (true) {
            Str::contains($category, 'senior') => 1997 + ($index % 8),
            Str::contains($category, 'junior') => 2008 + ($index % 2),
            Str::contains($category, 'cadete') => 2010 + ($index % 2),
            Str::contains($category, 'infantil') => 2012 + ($index % 2),
            default => 2005 + ($index % 4),
        };

        $month = str_pad((string) (($index % 12) + 1), 2, '0', STR_PAD_LEFT);
        $day = str_pad((string) ((($index * 2) % 27) + 1), 2, '0', STR_PAD_LEFT);

        return $year.'-'.$month.'-'.$day;
    }
}
