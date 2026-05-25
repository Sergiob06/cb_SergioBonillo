<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /** @var array<int, string> */
    private array $categories = [
        'Senior',
        'Junior',
        'Cadete',
        'Infantil',
        'Senior Masculino',
        'Senior Femenino',
    ];

    public function run(): void
    {
        foreach ($this->categories as $category) {
            Category::updateOrCreate(['name' => $category]);
        }
    }
}
