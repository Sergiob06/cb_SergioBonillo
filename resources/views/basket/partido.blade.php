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

    @if($partido->es_jugado && $partido->estadisticasEquipos->isNotEmpty())
        <div class="caja-equipo full-width" style="margin-bottom: 24px;">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Estadísticas del partido</h3>
                    <p>Datos del equipo local y del equipo visitante.</p>
                </div>
            </div>
        </div>

        <div class="rejilla-detalles">
            @foreach($partido->estadisticasEquipos->sortByDesc('es_local') as $estadistica)
                <div class="caja-detalle">
                    <div class="detalle-header"><i class="fas fa-basketball-ball icon-red"></i><h4>{{ $estadistica->equipo?->nombre ?? 'Equipo' }} · {{ $estadistica->es_local ? 'Local' : 'Visitante' }}</h4></div>
                    <div class="fila-detalle"><span>Puntos anotados</span><div class="valor"><strong>{{ $estadistica->puntos_anotados ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>T2 intentados</span><div class="valor"><strong>{{ $estadistica->t2_intentados ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>T3 intentados</span><div class="valor"><strong>{{ $estadistica->t3_intentados ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>TL intentados</span><div class="valor"><strong>{{ $estadistica->tl_intentados ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Balones perdidos</span><div class="valor"><strong>{{ $estadistica->balones_perdidos ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Rebotes ofensivos</span><div class="valor"><strong>{{ $estadistica->rebotes_ofensivos ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Rebotes defensivos</span><div class="valor"><strong>{{ $estadistica->rebotes_defensivos ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Tiros anotados</span><div class="valor"><strong>{{ $estadistica->tiros_anotados ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Asistencias</span><div class="valor"><strong>{{ $estadistica->asistencias ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Robos</span><div class="valor"><strong>{{ $estadistica->robos ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Tapones</span><div class="valor"><strong>{{ $estadistica->tapones ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Faltas</span><div class="valor"><strong>{{ $estadistica->faltas ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Eficiencia ofensiva</span><div class="valor"><strong>{{ $estadistica->eficiencia_ofensiva ?? '-' }}</strong></div></div>
                    <div class="fila-detalle"><span>Eficiencia defensiva</span><div class="valor"><strong>{{ $estadistica->eficiencia_defensiva ?? '-' }}</strong></div></div>
                </div>
            @endforeach
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
