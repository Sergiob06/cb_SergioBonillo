<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Str;

class PublicImageCatalog
{
    public const TEAM_FALLBACK = 'img/equipo-default.svg';

    public const PLAYER_FALLBACK = 'img/basket.jpeg';

    /** @var array<int, string> */
    private const TEAM_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

    /** @var array<int, string> */
    private const PLAYER_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public static function teamImageFor(string $teamName, int $offset = 0): string
    {
        return self::imageFor('escudos', $teamName, self::TEAM_EXTENSIONS, self::TEAM_FALLBACK, $offset);
    }

    public static function playerImageFor(string $playerName, int $offset = 0): string
    {
        return self::imageFor('jugadores', $playerName, self::PLAYER_EXTENSIONS, self::PLAYER_FALLBACK, $offset);
    }

    /**
     * @return array<int, string>
     */
    public static function teamImages(): array
    {
        return self::imagesFrom('escudos', self::TEAM_EXTENSIONS);
    }

    /**
     * @return array<int, string>
     */
    public static function playerImages(): array
    {
        return self::imagesFrom('jugadores', self::PLAYER_EXTENSIONS);
    }

    /**
     * @param  array<int, string>  $extensions
     */
    private static function imageFor(string $directory, string $name, array $extensions, string $fallback, int $offset = 0): string
    {
        $images = self::imagesFrom($directory, $extensions);

        if ($images === []) {
            return $fallback;
        }

        $slug = Str::slug($name);
        $matched = collect($images)->first(function (string $image) use ($slug) {
            return Str::contains(Str::slug(pathinfo($image, PATHINFO_FILENAME)), $slug);
        });

        if ($matched) {
            return $matched;
        }

        $index = (abs(crc32($slug)) + $offset) % count($images);

        return $images[$index];
    }

    /**
     * @param  array<int, string>  $extensions
     * @return array<int, string>
     */
    private static function imagesFrom(string $directory, array $extensions): array
    {
        $basePath = public_path($directory);

        if (! is_dir($basePath)) {
            return [];
        }

        return collect(scandir($basePath) ?: [])
            ->filter(function (string $filename) use ($basePath, $extensions) {
                return is_file($basePath.DIRECTORY_SEPARATOR.$filename)
                    && in_array(Str::lower(pathinfo($filename, PATHINFO_EXTENSION)), $extensions, true);
            })
            ->sort()
            ->map(fn (string $filename) => $directory.'/'.$filename)
            ->values()
            ->all();
    }
}
