{{-- LAYOUT PRINCIPAL --}}
@extends('layouts.app')

{{-- TÍTULO DE LA PESTAÑA --}}
@section('title', 'Inicio - Bellreguard Club de Basket')

{{-- CONTENIDO DE LA PÁGINA --}}
@section('contenido')
@php
    $calcularMedia = function ($valor, $partidos) {
        return $partidos > 0 ? number_format($valor / $partidos, 1) : '0.0';
    };
@endphp

<section class="seccion-estadisticas-header">
    <div class="header-contenido">
        <h1>Estadísticas del Equipo</h1>
        <p>Datos y métricas de rendimiento por temporada</p>
        <div class="temporada-actual">
            <i class="fas fa-calendar-alt"></i>
            {{ $estadisticaSeleccionada ? $estadisticaSeleccionada->temporada : 'Sin estadísticas disponibles' }}
        </div>
    </div>
</section>

<section class="contenedor-estadisticas">
    @if($estadisticas->isNotEmpty() && $estadisticaSeleccionada)
        <div class="selector-temporadas" style="display: flex; flex-wrap: wrap; gap: 10px;">
            <select id="equipo-select" data-current="{{ $equipoSeleccionado }}" class="btn-temp equipo-select" aria-label="Seleccionar equipo">
                @foreach($equiposConEstadisticas as $equipoStat)
                    <option value="{{ $equipoStat->equipo_id }}" {{ (int) $equipoStat->equipo_id === (int) $equipoSeleccionado ? 'selected' : '' }}>
                        {{ $equipoStat->equipo?->nombre ?? 'Equipo' }} - {{ $equipoStat->temporada ?? '2025/2026' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 20px; color: #555;">
            <strong>{{ $estadisticaSeleccionada->equipo?->nombre ?? 'Equipo sin asignar' }}</strong>
            <span style="margin-left: 8px;">{{ $estadisticaSeleccionada->equipo?->categoria ?? '' }}</span>
        </div>

        <div class="rejilla-stats-top">
            <div class="card-stat">
                <div class="icon-box red"><i class="fas fa-bullseye"></i></div>
                <div class="stat-info">
                    <h3>{{ $estadisticaSeleccionada->puntos_totales }}</h3>
                    <p>Puntos Totales</p>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box orange"><i class="fas fa-redo"></i></div>
                <div class="stat-info">
                    <h3>{{ $estadisticaSeleccionada->rebotes }}</h3>
                    <p>Rebotes</p>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box red-dark"><i class="fas fa-hand-paper"></i></div>
                <div class="stat-info">
                    <h3>{{ $estadisticaSeleccionada->asistencias }}</h3>
                    <p>Asistencias</p>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box red-soft"><i class="fas fa-shield-alt"></i></div>
                <div class="stat-info">
                    <h3>{{ $estadisticaSeleccionada->robos }}</h3>
                    <p>Robos</p>
                </div>
            </div>
        </div>

        <div class="rejilla-detalles">
            <div class="caja-detalle">
                <div class="detalle-header">
                    <i class="fas fa-basketball-ball icon-red"></i>
                    <h4>Rendimiento General</h4>
                </div>
                <div class="fila-detalle">
                    <span>Puntos Totales</span>
                    <div class="valor">
                        <strong>{{ $estadisticaSeleccionada->puntos_totales }}</strong>
                        <small>{{ $calcularMedia($estadisticaSeleccionada->puntos_totales, $estadisticaSeleccionada->partidos_jugados) }} por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Asistencias</span>
                    <div class="valor">
                        <strong>{{ $estadisticaSeleccionada->asistencias }}</strong>
                        <small>{{ $calcularMedia($estadisticaSeleccionada->asistencias, $estadisticaSeleccionada->partidos_jugados) }} por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Robos</span>
                    <div class="valor">
                        <strong>{{ $estadisticaSeleccionada->robos }}</strong>
                        <small>{{ $calcularMedia($estadisticaSeleccionada->robos, $estadisticaSeleccionada->partidos_jugados) }} por partido</small>
                    </div>
                </div>
            </div>

            <div class="caja-detalle">
                <div class="detalle-header">
                    <i class="fas fa-chart-line icon-red"></i>
                    <h4>Rendimiento Defensivo</h4>
                </div>
                <div class="fila-detalle">
                    <span>Rebotes Defensivos</span>
                    <div class="valor">
                        <strong>{{ $estadisticaSeleccionada->rebotes_defensivos }}</strong>
                        <small>{{ $calcularMedia($estadisticaSeleccionada->rebotes_defensivos, $estadisticaSeleccionada->partidos_jugados) }} por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Rebotes Ofensivos</span>
                    <div class="valor">
                        <strong>{{ $estadisticaSeleccionada->rebotes_ofensivos }}</strong>
                        <small>{{ $calcularMedia($estadisticaSeleccionada->rebotes_ofensivos, $estadisticaSeleccionada->partidos_jugados) }} por partido</small>
                    </div>
                </div>
                <div class="fila-detalle">
                    <span>Tapones</span>
                    <div class="valor">
                        <strong>{{ $estadisticaSeleccionada->tapones }}</strong>
                        <small>{{ $calcularMedia($estadisticaSeleccionada->tapones, $estadisticaSeleccionada->partidos_jugados) }} por partido</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="rejilla-resumen-final">
            <div class="card-resumen red">
                <div class="resumen-head">
                    <i class="fas fa-trophy"></i>
                    <span>Partidos</span>
                </div>
                <div class="resumen-body">
                    <h2>{{ $estadisticaSeleccionada->partidos_jugados }}</h2>
                    <p>Partidos Jugados</p>
                </div>
            </div>
            <div class="card-resumen green">
                <div class="resumen-head">
                    <i class="fas fa-check-circle"></i>
                    <span>Victorias</span>
                </div>
                <div class="resumen-body">
                    <h2>{{ $estadisticaSeleccionada->victorias }}</h2>
                    <p>Partidos Ganados</p>
                </div>
            </div>
            <div class="card-resumen dark">
                <div class="resumen-head">
                    <i class="fas fa-times-circle"></i>
                    <span>Derrotas</span>
                </div>
                <div class="resumen-body">
                    <h2>{{ $estadisticaSeleccionada->derrotas }}</h2>
                    <p>Partidos Perdidos</p>
                </div>
            </div>
        </div>
    @else
        <div class="caja-equipo full-width">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Sin estadísticas disponibles</h3>
                    <p>Cuando el administrador añada registros, aparecerán aquí automáticamente.</p>
                </div>
            </div>
        </div>
    @endif
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const equipoSelect = document.getElementById('equipo-select');

    if (!equipoSelect) {
        return;
    }

    equipoSelect.addEventListener('change', function () {
        const equipoId = this.value;
        const equipoActual = this.dataset.current;

        if (!equipoId || equipoId === equipoActual) {
            return;
        }

        const url = new URL('{{ route('basket.estadisticas') }}', window.location.origin);
        url.searchParams.set('equipo', equipoId);
        window.location.href = url.toString();
    });
});
</script>

@endsection
