<?php

namespace Database\Seeders;

use App\Models\Posicion;
use Illuminate\Database\Seeder;

class PosicionSeeder extends Seeder
{
    /** @var array<int, string> */
    private array $positions = [
        'Base',
        'Escolta',
        'Alero',
        'Ala-pívot',
        'Pívot',
    ];

    public function run(): void
    {
        foreach ($this->positions as $position) {
            Posicion::updateOrCreate(['nombre' => $position]);
        }
    }
}
