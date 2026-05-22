<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\ImagePath;

class Equipo extends Model
{
    use HasFactory; // IMPORTANTE PARA EL FACTORY

    public const IMAGE_DIRECTORIES = ['escudos', 'fotos/equipos', 'fotos'];

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

    protected $casts = [
        'es_local' => 'boolean',
    ];

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }

    public function scopeLocales($query)
    {
        return $query->where('es_local', true);
    }

    public function scopeExternos($query)
    {
        return $query->where('es_local', false);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class, 'equipo_local_id');
    }

    public function partidosComoLocal()
    {
        return $this->hasMany(Partido::class, 'equipo_local_id');
    }

    public function partidosComoVisitante()
    {
        return $this->hasMany(Partido::class, 'equipo_visitante_id');
    }

    public function partidosConEstadisticas()
    {
        return $this->hasMany(Partido::class, 'estadisticas_equipo_id');
    }

    public function getImageUrlAttribute(): string
    {
        return ImagePath::urlFromDirectories(
            $this->imagen_club,
            self::IMAGE_DIRECTORIES,
            ImagePath::DEFAULT_TEAM_IMAGE
        );
    }

    public function getImageAttribute(): ?string
    {
        return ImagePath::normalizeFromDirectories($this->imagen_club, self::IMAGE_DIRECTORIES);
    }
}
