<?php

namespace Database\Seeders;

use App\Models\Galeria;
use Illuminate\Database\Seeder;

class GaleriaSeeder extends Seeder
{
    /**
     * @var array<int, array{titulo:string,descripcion:string,imagen:string,fecha_imagen:string}>
     */
    private array $photos = [
        [
            'titulo' => 'Inicio de temporada',
            'descripcion' => 'Primer entrenamiento de la temporada 2025/2026.',
            'imagen' => 'img/intro.jpeg',
            'fecha_imagen' => '2025-09-15',
        ],
        [
            'titulo' => 'Partido en casa',
            'descripcion' => 'Jornada de liga en el Pabellon Municipal de Bellreguard.',
            'imagen' => 'img/final.jpeg',
            'fecha_imagen' => '2026-01-20',
        ],
        [
            'titulo' => 'Cantera Bellreguard',
            'descripcion' => 'Sesion de formacion con equipos de base del club.',
            'imagen' => 'img/basket.jpeg',
            'fecha_imagen' => '2026-02-10',
        ],
    ];

    public function run(): void
    {
        foreach ($this->photos as $photo) {
            Galeria::updateOrCreate(
                ['titulo' => $photo['titulo']],
                [
                    'descripcion' => $photo['descripcion'],
                    'imagen' => $this->imagePath($photo['imagen']),
                    'fecha_imagen' => $photo['fecha_imagen'],
                ]
            );
        }
    }

    private function imagePath(string $preferredImage): string
    {
        return is_file(public_path($preferredImage)) ? $preferredImage : 'img/basket.jpeg';
    }
}
