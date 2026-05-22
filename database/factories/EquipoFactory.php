<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Equipo;
use Database\Seeders\Support\LocalLeagueCatalog;
use Database\Seeders\Support\PublicImageCatalog;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipo>
 */
class EquipoFactory extends Factory
{
    protected $model = Equipo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('es_ES');
        $category = Category::query()->inRandomOrder()->first() ?? Category::query()->create([
            'name' => $faker->randomElement(LocalLeagueCatalog::categories()),
        ]);
        $clubCity = $faker->randomElement([
            'Bellreguard',
            'Gandia',
            'Oliva',
            'Denia',
            'Xativa',
            'Alzira',
            'Cullera',
            'Sueca',
            'Tavernes',
            'Pego',
            'Xeraco',
            'Carcaixent',
            'Algemesi',
            'Canals',
            'Ontinyent',
            'Carlet',
        ]);
        $clubBrand = $faker->randomElement([
            'Basket Club',
            'CB',
            'Basquet',
            'Basket',
            'Hoops Club',
        ]);
        $clubSuffix = $faker->unique()->numberBetween(1, 9999);
        $teamName = trim($clubBrand === 'CB'
            ? sprintf('%s %s %d', $clubBrand, $clubCity, $clubSuffix)
            : sprintf('%s %s %d', $clubCity, $clubBrand, $clubSuffix)
        );

        return [
            'nombre' => $teamName,
            'categoria' => $category->name,
            'category_id' => $category->id,
            'imagen_club' => PublicImageCatalog::teamImageFor($teamName),
            'descripcion' => $faker->paragraph(3),
            'numero_jugadores' => 0,
            'es_local' => true,
        ];
    }
}
