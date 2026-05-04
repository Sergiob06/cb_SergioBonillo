<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Equipo extends Model
{
    use HasFactory; // IMPORTANTE PARA EL FACTORY

    protected $fillable = [
        'nombre', 
        'categoria',
        'category_id',
        'imagen_club',
        'descripcion',
        'numero_jugadores',
        'es_local',
    ];

    protected $appends = [
        'image',
        'image_url',
    ];

    public function jugadores() {
        return $this->hasMany(Jugador::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }

    public function estadisticas()
    {
        return $this->hasMany(Estadistica::class);
    }

    public function clasificaciones()
    {
        return $this->hasMany(Clasificacion::class);
    }

    public function getImageUrlAttribute(): string
    {
        $image = $this->image;

        if (!$image) {
            return asset('img/basket.jpeg');
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        if (Str::startsWith($image, ['storage/', '/storage/'])) {
            return asset(ltrim($image, '/'));
        }

        if (Str::startsWith($image, ['escudos/', 'jugadores/', 'products/', 'galeria/'])) {
            return asset('storage/' . ltrim($image, '/'));
        }

        return asset('storage/' . ltrim($image, '/'));
    }

    public function getImageAttribute(): ?string
    {
        if (!$this->imagen_club) {
            return null;
        }

        $normalized = str_replace('\\', '/', trim($this->imagen_club));

        if ($normalized === '') {
            return null;
        }

        if (Str::startsWith($normalized, ['http://', 'https://', 'storage/', '/storage/'])) {
            return ltrim($normalized, '/');
        }

        if (Str::contains($normalized, '/')) {
            return ltrim($normalized, '/');
        }

        if (Str::startsWith($normalized, 'escudos/')) {
            return $normalized;
        }

        return 'escudos/' . ltrim($normalized, '/');
    }
}
