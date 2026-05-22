<?php

namespace App\Models;

use App\Support\ImagePath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'posicion_id',
        'imagen_jugador',
        'equipo_id',
    ];

    protected $appends = [
        'image',
        'image_url',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function posicion()
    {
        return $this->belongsTo(Posicion::class);
    }

    public function getPosicionNombreAttribute(): string
    {
        return $this->posicion?->nombre ?? $this->posicion ?? 'Sin posición';
    }

    public function scopeDeEquiposLocales($query)
    {
        return $query->whereHas('equipo', function ($equipoQuery) {
            $equipoQuery->locales();
        });
    }

    public function getImageAttribute(): ?string
    {
        return ImagePath::publicPath($this->imagen_jugador, 'jugadores');
    }

    public function getImageUrlAttribute(): string
    {
        return ImagePath::publicUrl($this->imagen_jugador, 'jugadores');
    }
}
