<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Support\SeasonSimulationStore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        SeasonSimulationStore::reset();

        User::firstOrCreate(
            ['email' => 'admin@bellreguard.com'],
            [
                'name' => 'Admin Bellreguard',
                'password' => Hash::make('12345678'),
            ]
        );

        $this->call([
            EquipoSeeder::class,
            JugadorSeeder::class,
            PartidoSeeder::class,
            EstadisticaSeeder::class,
            ProductSeeder::class,
            GaleriaSeeder::class,
        ]);
    }
}
