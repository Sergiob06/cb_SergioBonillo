@extends('layouts.admin')

@section('contenido_admin')
@php($esAdmin = auth()->user()?->rol === 'admin')
<header class="header-admin">
    <div>
        <h2>Ficha del Partido</h2>
        <p style="color: #777;">Detalle del encuentro programado</p>
    </div>

    <a href="{{ route('partidos.index') }}" class="btn-nuevo" style="background-color: #777;">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</header>

<div class="pizarra-admin">
    @if(session('mensaje'))
        <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('mensaje') }}
        </div>
    @endif

    @if(session('mensaje_error'))
        <div style="padding: 15px; background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 5px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('mensaje_error') }}
        </div>
    @endif

    <div class="admin-detail-layout admin-detail-layout--match">
        <div class="admin-detail-panel admin-detail-panel--compact">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fb8500; display: inline-block;">Encuentro</h3>

            <p style="font-size: 1.2rem; margin: 20px 0 10px;">
                <strong>{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }}</strong>
            </p>
            <p style="margin: 0 0 10px; color: #777;">vs</p>
            <p style="font-size: 1.2rem; margin: 0;">
                <strong>{{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</strong>
            </p>
        </div>

        <div class="admin-detail-content">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fb8500; display: inline-block;">Información General</h3>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Fecha y hora:</strong> {{ $partido->fecha_partido->format('d/m/Y H:i') }}
            </p>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Estado:</strong> <span class="estado-partido estado-partido--{{ $partido->estado }}">{{ $partido->estado_nombre }}</span>
            </p>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Lugar:</strong> {{ $partido->lugar }}
            </p>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Resultado:</strong> {{ $partido->resultado }}
            </p>

            @if(!$partido->es_jugado)
                <p style="font-size: 1.1rem; margin: 15px 0;">
                    <strong>Estadísticas:</strong> disponibles cuando el partido esté marcado como jugado.
                </p>
            @elseif($partido->estadisticasEquipos->isNotEmpty())
                <p style="font-size: 1.1rem; margin: 15px 0;">
                    <strong>Estadísticas:</strong>
                    registradas para local y visitante.
                </p>
            @else
                <p style="font-size: 1.1rem; margin: 15px 0;">
                    <strong>Estadísticas:</strong>
                    pendientes de completar desde la edición del partido.
                </p>
            @endif

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <div class="admin-detail-actions">
                @if($esAdmin)
                    <a href="{{ route('partidos.edit', $partido->id) }}" class="btn-accion editar" title="Editar partido" style="margin: 0;">
                        <i class="fas fa-pen"></i>
                    </a>
                @endif

                @if($partido->es_jugado)
                    <a href="{{ route('partidos.estadisticas.edit', $partido->id) }}" class="btn-accion" title="{{ $partido->tiene_estadisticas_equipo ? 'Editar estadísticas' : 'Añadir estadísticas' }}" style="background-color: #023e8a; color: white;">
                        <i class="fas fa-chart-bar"></i>
                    </a>
                @else
                    <span class="btn-accion" title="Las estadísticas solo pueden añadirse cuando el partido ya ha sido jugado." style="background-color: #bbb; color: white; cursor: not-allowed;">
                        <i class="fas fa-chart-bar"></i>
                    </span>
                @endif

                @if($esAdmin)
                    <form action="{{ route('partidos.destroy', $partido->id) }}" method="POST" onsubmit="return confirm('¿Eliminar partido?')" class="admin-detail-inline-form" style="margin: 0; display: flex; align-items: center;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if($partido->es_jugado && $partido->estadisticasEquipos->isNotEmpty())
        <div class="admin-detail-body" style="margin-top: 25px;">
            <h3 style="margin-top: 0;">Estadísticas por equipo</h3>

            <div class="rejilla-detalles">
                @foreach($partido->estadisticasEquipos->sortByDesc('es_local') as $estadistica)
                    <div class="caja-detalle">
                        <div class="detalle-header">
                            <i class="fas fa-chart-line icon-red"></i>
                            <h4>{{ $estadistica->equipo?->nombre ?? 'Equipo' }} · {{ $estadistica->es_local ? 'Local' : 'Visitante' }}</h4>
                        </div>
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
        </div>
    @endif
</div>
@endsection
