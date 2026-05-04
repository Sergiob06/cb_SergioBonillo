<?php

namespace Database\Seeders\Support;

use App\Models\Equipo;
use Illuminate\Support\Collection;

class SeasonSimulationStore
{
    /**
     * @var array{temporada:string,fixtures:Collection<int, array<string, mixed>>,standings:Collection<int, array<string, mixed>>,statistics:Collection<int, array<string, mixed>>}|null
     */
    private static ?array $payload = null;

    /**
     * @param Collection<int, Equipo> $equipos
     * @return array{temporada:string,fixtures:Collection<int, array<string, mixed>>,standings:Collection<int, array<string, mixed>>,statistics:Collection<int, array<string, mixed>>}
     */
    public static function getOrBuild(Collection $equipos, string $temporada = '2025/2026'): array
    {
        if (self::$payload === null) {
            self::$payload = SeasonSimulationBuilder::build($equipos, $temporada);
        }

        return self::$payload;
    }

    public static function reset(): void
    {
        self::$payload = null;
    }
}
