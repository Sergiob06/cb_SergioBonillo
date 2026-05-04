<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{
    use HasFactory;

    protected $table = 'clasificaciones';

    protected $fillable = [
        'equipo_id',
        'equipo_nombre',
        'categoria',
        'temporada',
        'posicion',
        'partidos_jugados',
        'partidos_ganados',
        'partidos_perdidos',
        'puntos_favor',
        'puntos_contra',
        'puntos',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
