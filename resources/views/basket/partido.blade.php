@extends('layouts.app')

@section('title', 'Detalle del partido - Bellreguard Club de Basket')

@section('contenido')
@php
    $nombreLocal = $partido->equipoLocal?->nombre ?? $partido->equipo_local;
    $nombreVisitante = $partido->equipoVisitante?->nombre ?? $partido->equipo_visitante;
    $logoLocal = $partido->equipoLocal?->image_url ?? asset(\App\Support\ImagePath::DEFAULT_TEAM_IMAGE);
    $logoVisitante = $partido->equipoVisitante?->image_url ?? asset(\App\Support\ImagePath::DEFAULT_TEAM_IMAGE);
    $marcador = $partido->es_jugado ? $partido->resultado : '-';
@endphp

<section class="seccion-partidos-header partido-detalle-header">
    <div class="header-contenido">
        <div class="header-texto">
            <h1>{{ $nombreLocal }} vs {{ $nombreVisitante }}</h1>
            <div class="partido-detalle-meta">
                <span><i class="far fa-calendar-alt"></i> {{ $partido->fecha_partido->translatedFormat('d F Y') }}</span>
                <span><i class="far fa-clock"></i> {{ $partido->fecha_partido->format('H:i') }}h</span>
                <span><i class="fas fa-map-marker-alt"></i> {{ $partido->lugar }}</span>
                <span>{{ $partido->estado_nombre }}</span>
            </div>
        </div>
    </div>
</section>

<section class="contenedor-estadisticas partido-detalle-contenido">
    <div class="partido-score-card">
        <div class="partido-equipo partido-equipo-local">
            <div class="partido-logo-wrap">
                <img src="{{ $logoLocal }}" alt="{{ $nombreLocal }}" class="partido-logo">
            </div>
            <span class="partido-rol">Local</span>
            <h2>{{ $nombreLocal }}</h2>
        </div>

        <div class="partido-marcador">
            <strong>{{ $marcador }}</strong>
        </div>

        <div class="partido-equipo partido-equipo-visitante">
            <div class="partido-logo-wrap">
                <img src="{{ $logoVisitante }}" alt="{{ $nombreVisitante }}" class="partido-logo">
            </div>
            <span class="partido-rol">Visitante</span>
            <h2>{{ $nombreVisitante }}</h2>
        </div>
    </div>

    @if($partido->es_jugado && $partido->tiene_estadisticas_equipo)
        <div class="caja-equipo full-width" style="margin-bottom: 24px;">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Estadísticas de {{ $partido->equipo_estadisticas_resuelto?->nombre ?? 'Bellreguard' }}</h3>
                    <p>Datos registrados únicamente para el equipo Bellreguard participante.</p>
                </div>
            </div>
        </div>

        <div class="rejilla-stats-top">
            <div class="card-stat"><div class="icon-box red"><i class="fas fa-bullseye"></i></div><div class="stat-info"><h3>{{ $partido->puntos_anotados }}</h3><p>Puntos anotados</p></div></div>
            <div class="card-stat"><div class="icon-box orange"><i class="fas fa-redo"></i></div><div class="stat-info"><h3>{{ $partido->rebotes }}</h3><p>Rebotes</p></div></div>
            <div class="card-stat"><div class="icon-box red-dark"><i class="fas fa-hand-paper"></i></div><div class="stat-info"><h3>{{ $partido->asistencias }}</h3><p>Asistencias</p></div></div>
            <div class="card-stat"><div class="icon-box red-soft"><i class="fas fa-shield-alt"></i></div><div class="stat-info"><h3>{{ $partido->robos }}</h3><p>Robos</p></div></div>
        </div>

        <div class="rejilla-detalles">
            <div class="caja-detalle">
                <div class="detalle-header"><i class="fas fa-basketball-ball icon-red"></i><h4>Ataque</h4></div>
                <div class="fila-detalle"><span>Triples</span><div class="valor"><strong>{{ $partido->triples }}</strong></div></div>
                <div class="fila-detalle"><span>Tiros libres</span><div class="valor"><strong>{{ $partido->tiros_libres }}</strong></div></div>
                <div class="fila-detalle"><span>Asistencias</span><div class="valor"><strong>{{ $partido->asistencias }}</strong></div></div>
            </div>

            <div class="caja-detalle">
                <div class="detalle-header"><i class="fas fa-chart-line icon-red"></i><h4>Defensa</h4></div>
                <div class="fila-detalle"><span>Puntos recibidos</span><div class="valor"><strong>{{ $partido->puntos_recibidos }}</strong></div></div>
                <div class="fila-detalle"><span>Robos</span><div class="valor"><strong>{{ $partido->robos }}</strong></div></div>
                <div class="fila-detalle"><span>Pérdidas</span><div class="valor"><strong>{{ $partido->perdidas }}</strong></div></div>
                <div class="fila-detalle"><span>Faltas</span><div class="valor"><strong>{{ $partido->faltas }}</strong></div></div>
            </div>
        </div>
    @elseif($partido->es_jugado)
        <div class="caja-equipo full-width">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Sin estadísticas disponibles</h3>
                    <p>Cuando el administrador registre las estadísticas del partido aparecerán aquí.</p>
                </div>
            </div>
        </div>
    @else
        <div class="caja-equipo full-width">
            <div class="titulo-equipo">
                <i class="fas fa-clock"></i>
                <div>
                    <h3>Partido próximo</h3>
                    <p>Las estadísticas estarán disponibles cuando el partido se haya jugado.</p>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection
