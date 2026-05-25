<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaEquipo extends Model
{
    use HasFactory;

    protected $table = 'estadisticas_equipos';

    protected $fillable = [
        'partido_id',
        'equipo_id',
        'es_local',
        'puntos_anotados',
        't2_intentados',
        't3_intentados',
        'tl_intentados',
        'balones_perdidos',
        'rebotes_ofensivos',
        'tiros_anotados',
        'rebotes_defensivos',
        'asistencias',
        'robos',
        'tapones',
        'faltas',
    ];

    protected $casts = [
        'es_local' => 'boolean',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function getRebotesTotalesAttribute(): ?int
    {
        if ($this->rebotes_ofensivos === null && $this->rebotes_defensivos === null) {
            return null;
        }

        return (int) ($this->rebotes_ofensivos ?? 0) + (int) ($this->rebotes_defensivos ?? 0);
    }

    public function getEficienciaOfensivaAttribute(): ?float
    {
        foreach (['puntos_anotados', 't2_intentados', 't3_intentados', 'tl_intentados', 'balones_perdidos', 'rebotes_ofensivos'] as $campo) {
            if ($this->{$campo} === null) {
                return null;
            }
        }

        $posesiones = $this->t2_intentados
            + $this->t3_intentados
            + ($this->tl_intentados / 2)
            + $this->balones_perdidos
            - $this->rebotes_ofensivos;

        if ($posesiones <= 0) {
            return null;
        }

        return round($this->puntos_anotados / $posesiones, 3);
    }

    public function getEficienciaDefensivaAttribute(): ?float
    {
        return $this->estadisticaRival()?->eficiencia_ofensiva;
    }

    public function estadisticaRival(): ?self
    {
        $estadisticas = $this->relationLoaded('partido')
            ? $this->partido?->estadisticasEquipos
            : $this->partido()->with('estadisticasEquipos')->first()?->estadisticasEquipos;

        return $estadisticas?->first(fn (self $estadistica) => (int) $estadistica->equipo_id !== (int) $this->equipo_id);
    }
}
