<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipo;
use Database\Seeders\Support\LocalLeagueCatalog;
use Database\Seeders\Support\PublicImageCatalog;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = collect(LocalLeagueCatalog::categories())
            ->map(fn (string $name) => Category::firstOrCreate(['name' => $name]));

        foreach (LocalLeagueCatalog::clubs() as $club) {
            $category = $categorias->firstWhere('name', $club['categoria']);

            Equipo::updateOrCreate([
                'nombre' => $club['name'],
            ], [
                'nombre' => $club['name'],
                'categoria' => $category->name,
                'category_id' => $category->id,
                'imagen_club' => PublicImageCatalog::teamImageFor($club['name']),
                'descripcion' => 'Equipo de ' . $club['ciudad'] . ' que compite en categoria ' . strtolower($category->name) . ' dentro de una simulacion de liga local.',
                'es_local' => $club['es_local'],
            ]);
        }
    }
}
