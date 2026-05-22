<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estadistica extends Model
{
    use HasFactory;

    protected $table = 'estadisticas';

    protected $fillable = [
        'partido_id',
        'equipo_id',
        'temporada',
        'puntos_totales',
        'rebotes',
        'asistencias',
        'robos',
        'rebotes_defensivos',
        'rebotes_ofensivos',
        'tapones',
        'partidos_jugados',
        'victorias',
        'derrotas',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }
}
