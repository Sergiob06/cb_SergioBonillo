<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Jugador extends Model
{
    use HasFactory; // IMPORTANTE PARA EL FACTORY

    protected $table = 'jugadores';
    
    protected $fillable = [
        'nombre', 
        'apellido', 
        'dorsal', 
        'fecha_nacimiento', 
        'posicion', 
        'imagen_jugador',
        'equipo_id',
    ];

    protected $appends = [
        'image',
        'image_url',
    ];

    public function equipo() {
        return $this->belongsTo(Equipo::class);
    }

    public function getImageAttribute(): ?string
    {
        if (!$this->imagen_jugador) {
            return null;
        }

        $normalized = str_replace('\\', '/', trim($this->imagen_jugador));

        if ($normalized === '') {
            return null;
        }

        if (Str::startsWith($normalized, ['http://', 'https://', 'storage/', '/storage/'])) {
            return ltrim($normalized, '/');
        }

        if (Str::startsWith($normalized, 'jugadores/')) {
            return $normalized;
        }

        return 'jugadores/' . ltrim($normalized, '/');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('img/basket.jpeg');
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }
}
