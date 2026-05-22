<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;

    protected $table = 'partidos';

    protected $fillable = [
        'equipo_local_id',
        'equipo_visitante_id',
        'estadisticas_equipo_id',
        'category_id',
        'equipo_local',
        'equipo_visitante',
        'fecha_partido',
        'estado',
        'lugar',
        'puntos_local',
        'puntos_visitante',
        'triples',
        'tiros_libres',
        'rebotes',
        'asistencias',
        'robos',
        'perdidas',
        'faltas',
    ];

    protected $casts = [
        'fecha_partido' => 'datetime',
    ];

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function equipoEstadisticas()
    {
        return $this->belongsTo(Equipo::class, 'estadisticas_equipo_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function estadistica()
    {
        return $this->hasOne(Estadistica::class);
    }

    public function scopeJugados($query)
    {
        return $query->where('estado', 'jugado');
    }

    public function scopeProximos($query)
    {
        return $query->where('estado', 'proximo');
    }

    public function getEsJugadoAttribute(): bool
    {
        return $this->estado === 'jugado';
    }

    public function getEstadoNombreAttribute(): string
    {
        return $this->es_jugado ? 'Jugado' : 'Próximo';
    }

    public function getResultadoAttribute(): string
    {
        if (!$this->es_jugado) {
            return 'Por jugar';
        }

        if ($this->puntos_local === null || $this->puntos_visitante === null) {
            return '-';
        }

        return $this->puntos_local . ' - ' . $this->puntos_visitante;
    }

    public function getPuntosAnotadosAttribute(): ?int
    {
        if ($this->estadisticasPertenecenAlVisitante()) {
            return $this->puntos_visitante;
        }

        if ($this->estadisticasPertenecenAlLocal()) {
            return $this->puntos_local;
        }

        return null;
    }

    public function getPuntosRecibidosAttribute(): ?int
    {
        if ($this->estadisticasPertenecenAlVisitante()) {
            return $this->puntos_local;
        }

        if ($this->estadisticasPertenecenAlLocal()) {
            return $this->puntos_visitante;
        }

        return null;
    }

    public function getDiferenciaPuntosAttribute(): ?int
    {
        if ($this->puntos_anotados === null || $this->puntos_recibidos === null) {
            return null;
        }

        return $this->puntos_anotados - $this->puntos_recibidos;
    }

    public function getTieneEstadisticasEquipoAttribute(): bool
    {
        return $this->es_jugado
            && $this->puntos_local !== null
            && $this->puntos_visitante !== null
            && $this->equipo_estadisticas_resuelto !== null
            && collect(['triples', 'tiros_libres', 'rebotes', 'asistencias', 'robos', 'perdidas', 'faltas'])
                ->every(fn (string $campo) => $this->{$campo} !== null);
    }

    public function getEquipoEstadisticasResueltoAttribute(): ?Equipo
    {
        if ($this->estadisticas_equipo_id) {
            return $this->equipoEstadisticas;
        }

        if ($this->equipoLocal?->es_local) {
            return $this->equipoLocal;
        }

        if ($this->equipoVisitante?->es_local) {
            return $this->equipoVisitante;
        }

        return null;
    }

    public function participaEquipoLocal(): bool
    {
        return (bool) ($this->equipoLocal?->es_local || $this->equipoVisitante?->es_local);
    }

    private function estadisticasPertenecenAlLocal(): bool
    {
        if ($this->estadisticas_equipo_id) {
            return (int) $this->estadisticas_equipo_id === (int) $this->equipo_local_id;
        }

        return (bool) $this->equipoLocal?->es_local;
    }

    private function estadisticasPertenecenAlVisitante(): bool
    {
        if ($this->estadisticas_equipo_id) {
            return (int) $this->estadisticas_equipo_id === (int) $this->equipo_visitante_id;
        }

        return !$this->equipoLocal?->es_local && (bool) $this->equipoVisitante?->es_local;
    }
}
