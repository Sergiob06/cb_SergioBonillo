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

        $admin = User::firstOrCreate(
            ['email' => 'admin@bellreguard.com'],
            [
                'name' => 'Admin Bellreguard',
                'rol' => 'admin',
                'password' => Hash::make('12345678'),
            ]
        );

        $admin->forceFill(['rol' => 'admin'])->save();

        User::updateOrCreate(
            ['email' => 'entrenador@basketbellreguard.es'],
            [
                'name' => 'Entrenador Bellreguard',
                'rol' => 'entrenador',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            CategoriaSeeder::class,
            PosicionSeeder::class,
            EquipoSeeder::class,
            JugadorSeeder::class,
            PartidoSeeder::class,
            EstadisticaSeeder::class,
            ProductSeeder::class,
            ProductoSolicitudSeeder::class,
            GaleriaSeeder::class,
        ]);
    }
}
