<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Support\SeasonSimulationStore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        SeasonSimulationStore::reset();

        User::create([
            'name' => 'Admin Bellreguard',
            'email' => 'admin@bellreguard.com',
            'password' => '12345678',
        ]);

        $this->call([
            EquipoSeeder::class,
            JugadorSeeder::class,
            PartidoSeeder::class,
            ClasificacionSeeder::class,
            EstadisticaSeeder::class,
        ]);
    }
}
