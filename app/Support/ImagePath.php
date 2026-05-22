<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImagePath
{
    public const DEFAULT_IMAGE = 'img/basket.jpeg';
    public const DEFAULT_TEAM_IMAGE = 'img/equipo-default.svg';

    public static function normalize(?string $path, ?string $defaultDirectory = null): ?string
    {
        if (!$path) {
            return null;
        }

        $normalized = trim(str_replace('\\', '/', $path));

        if ($normalized === '') {
            return null;
        }

        if (Str::startsWith($normalized, ['http://', 'https://'])) {
            return $normalized;
        }

        $normalized = preg_replace('#^/?storage/#', '', $normalized);
        $normalized = preg_replace('#^/?public/#', '', $normalized);
        $normalized = ltrim($normalized, '/');

        if ($defaultDirectory && !Str::contains($normalized, '/')) {
            return trim($defaultDirectory, '/') . '/' . $normalized;
        }

        return $normalized;
    }

    public static function url(?string $path, ?string $defaultDirectory = null, string $fallback = self::DEFAULT_IMAGE): string
    {
        $normalized = self::normalize($path, $defaultDirectory);

        if (!$normalized) {
            return asset($fallback);
        }

        if (Str::startsWith($normalized, ['http://', 'https://'])) {
            return $normalized;
        }

        if (Storage::disk('public')->exists($normalized)) {
            return asset('storage/' . $normalized);
        }

        if (is_file(public_path($normalized))) {
            return asset($normalized);
        }

        return asset($fallback);
    }

    /**
     * Resolve a path that may be a full storage path or just a filename from one of several folders.
     *
     * @param array<int, string> $directories
     */
    public static function normalizeFromDirectories(?string $path, array $directories): ?string
    {
        $normalized = self::normalize($path);

        if (!$normalized) {
            return null;
        }

        if (Str::startsWith($normalized, ['http://', 'https://']) || Str::contains($normalized, '/')) {
            return $normalized;
        }

        $firstDirectory = $directories[0] ?? null;

        return $firstDirectory ? trim($firstDirectory, '/') . '/' . $normalized : $normalized;
    }

    /**
     * @param array<int, string> $directories
     */
    public static function urlFromDirectories(?string $path, array $directories, string $fallback = self::DEFAULT_IMAGE): string
    {
        $normalized = self::normalize($path);

        if (!$normalized) {
            return asset($fallback);
        }

        if (Str::startsWith($normalized, ['http://', 'https://'])) {
            return $normalized;
        }

        $candidates = Str::contains($normalized, '/')
            ? [$normalized]
            : collect($directories)
                ->map(fn (string $directory) => trim($directory, '/') . '/' . $normalized)
                ->push($normalized)
                ->unique()
                ->values()
                ->all();

        foreach ($candidates as $candidate) {
            if (Storage::disk('public')->exists($candidate)) {
                return asset('storage/' . $candidate);
            }

            if (is_file(public_path($candidate))) {
                return asset($candidate);
            }
        }

        return asset($fallback);
    }

    public static function deleteFromPublicDisk(?string $path, ?string $defaultDirectory = null): void
    {
        $normalized = self::normalize($path, $defaultDirectory);

        if ($normalized && !Str::startsWith($normalized, ['http://', 'https://'])) {
            Storage::disk('public')->delete($normalized);
        }
    }

    /**
     * @param array<int, string> $directories
     */
    public static function deleteFromDirectories(?string $path, array $directories): void
    {
        $normalized = self::normalize($path);

        if (!$normalized || Str::startsWith($normalized, ['http://', 'https://'])) {
            return;
        }

        if (Str::contains($normalized, '/')) {
            Storage::disk('public')->delete($normalized);
            return;
        }

        foreach ($directories as $directory) {
            Storage::disk('public')->delete(trim($directory, '/') . '/' . $normalized);
        }
    }
}
