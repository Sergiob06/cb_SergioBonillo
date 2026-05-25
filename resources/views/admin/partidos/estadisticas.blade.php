@extends('layouts.admin')

@section('contenido_admin')
@php
    $estadisticaLocal = $partido->estadisticasEquipos->firstWhere('es_local', true);
    $estadisticaVisitante = $partido->estadisticasEquipos->firstWhere('es_local', false);
    $camposEstadisticas = [
        'puntos_anotados' => 'Puntos anotados',
        't2_intentados' => 'T2 intentados',
        't3_intentados' => 'T3 intentados',
        'tl_intentados' => 'TL intentados',
        'balones_perdidos' => 'Balones perdidos',
        'rebotes_ofensivos' => 'Rebotes ofensivos',
        'tiros_anotados' => 'Tiros anotados',
        'rebotes_defensivos' => 'Rebotes defensivos',
        'asistencias' => 'Asistencias',
        'robos' => 'Robos',
        'tapones' => 'Tapones',
        'faltas' => 'Faltas',
    ];
@endphp

<div class="contenedor-edit-jugador">
    <header class="header-ficha">
        <div>
            <h2>Editar estadísticas</h2>
            <p>{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }} vs {{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</p>
        </div>
        <a href="{{ route('partidos.show', $partido) }}" class="btn-volver-link">
            <i class="fas fa-chevron-left"></i> Volver al partido
        </a>
    </header>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>¡Vaya! Algo ha ido mal:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pizarra-ficha">
        <form action="{{ route('partidos.estadisticas.update', $partido) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid-formulario-fichas">
                <div class="seccion-form-ficha seccion-deportiva admin-form-span-full">
                    <h3><i class="fas fa-calendar-check"></i> Partido jugado</h3>
                    <div class="admin-fixed-match">
                        <strong>{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }} vs {{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</strong>
                        <span>{{ $partido->fecha_partido?->format('d/m/Y H:i') }}{{ $partido->lugar ? ' · ' . $partido->lugar : '' }}</span>
                    </div>
                    <small class="admin-help-text">Solo se actualizan estadísticas. Los puntos anotados del local y del visitante actualizarán automáticamente el resultado final del partido.</small>
                </div>

                <div class="seccion-form-ficha seccion-personal admin-form-span-full">
                    <h3><i class="fas fa-chart-bar"></i> Estadísticas del equipo local</h3>
                    <small class="admin-help-text">{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }}</small>

                    <div class="admin-form-inline-grid" style="margin-top: 18px;">
                        @foreach($camposEstadisticas as $campo => $label)
                            <div class="campo-ficha">
                                <label>{{ $label }}</label>
                                <input type="number" min="0" name="estadisticas[local][{{ $campo }}]" value="{{ old('estadisticas.local.'.$campo, $estadisticaLocal?->{$campo}) }}" class="input-ficha" style="background: white;" {{ $campo === 'puntos_anotados' ? 'required' : '' }}>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="seccion-form-ficha seccion-personal admin-form-span-full">
                    <h3><i class="fas fa-chart-bar"></i> Estadísticas del equipo visitante</h3>
                    <small class="admin-help-text">{{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</small>

                    <div class="admin-form-inline-grid" style="margin-top: 18px;">
                        @foreach($camposEstadisticas as $campo => $label)
                            <div class="campo-ficha">
                                <label>{{ $label }}</label>
                                <input type="number" min="0" name="estadisticas[visitante][{{ $campo }}]" value="{{ old('estadisticas.visitante.'.$campo, $estadisticaVisitante?->{$campo}) }}" class="input-ficha" style="background: white;" {{ $campo === 'puntos_anotados' ? 'required' : '' }}>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-acciones-ficha">
                <a href="{{ route('partidos.show', $partido) }}" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha">
                    <i class="fas fa-sync-alt"></i> ACTUALIZAR ESTADÍSTICAS
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
