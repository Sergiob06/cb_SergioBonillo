@extends('layouts.app')

@section('title', $equipo->nombre . ' - Bellreguard Club de Basket')

@section('contenido')
<section class="seccion-equipo-header">
    <h1>{{ $equipo->nombre }}</h1>
    <p>{{ $equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría' }}</p>
</section>

<section class="equipo-jugadores equipo-detalle-main">
    <div class="caja-equipo full-width equipo-detalle-card">
        <div class="titulo-equipo">
            <img src="{{ $equipo->image_url }}" alt="{{ $equipo->nombre }}" class="equipo-detalle-logo">
            <div>
                <h3>{{ $equipo->nombre }}</h3>
                <p>{{ $equipo->descripcion ?: 'Equipo del Bellreguard Club de Basket.' }}</p>
            </div>
        </div>

        <div class="equipo-detalle-actions">
            <a href="{{ route('basket.partidos', ['equipo' => $equipo->id]) }}" class="btn-public btn-public--primary">
                Ver partidos
            </a>
            <a href="{{ route('basket.equipos') }}" class="btn-public btn-public--secondary">
                Volver a equipos
            </a>
        </div>
    </div>

    @if($equipo->es_local)
        <div class="caja-equipo full-width equipo-detalle-card equipo-detalle-section-title">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Análisis del equipo</h3>
                    <p>Resumen automático de los partidos jugados</p>
                </div>
            </div>

            @if(($analisisEquipo['partidos_jugados'] ?? 0) > 0)
                <div class="rejilla-stats-top estadistica-card-stats">
                    <div class="card-stat"><div class="stat-info"><h3>{{ $analisisEquipo['partidos_jugados'] }}</h3><p>Partidos</p></div></div>
                    <div class="card-stat"><div class="stat-info"><h3>{{ $analisisEquipo['victorias'] }}</h3><p>Victorias</p></div></div>
                    <div class="card-stat"><div class="stat-info"><h3>{{ $analisisEquipo['derrotas'] }}</h3><p>Derrotas</p></div></div>
                    <div class="card-stat"><div class="stat-info"><h3>{{ $analisisEquipo['diferencia_media'] }}</h3><p>Dif. media</p></div></div>
                </div>

                <div class="rejilla-detalles">
                    <div class="caja-detalle">
                        <div class="fila-detalle"><span>Media puntos anotados</span><div class="valor"><strong>{{ $analisisEquipo['media_puntos_anotados'] }}</strong></div></div>
                        <div class="fila-detalle"><span>Media puntos recibidos</span><div class="valor"><strong>{{ $analisisEquipo['media_puntos_recibidos'] }}</strong></div></div>
                    </div>
                    <div class="caja-detalle">
                        @php($mejor = $analisisEquipo['mejor_partido_ofensivo'])
                        @php($peor = $analisisEquipo['peor_partido_defensivo'])
                        <div class="fila-detalle"><span>Mejor partido ofensivo</span><div class="valor"><strong>{{ $mejor?->puntos_anotados ?? '-' }} pts</strong><small>{{ $mejor?->fecha_partido?->format('d/m/Y') }}</small></div></div>
                        <div class="fila-detalle"><span>Peor partido defensivo</span><div class="valor"><strong>{{ $peor?->puntos_recibidos ?? '-' }} recibidos</strong><small>{{ $peor?->fecha_partido?->format('d/m/Y') }}</small></div></div>
                    </div>
                </div>
            @else
                <div class="alerta-basket" style="margin-top: 18px;">
                    <p>Este equipo todavía no tiene partidos jugados con resultado para calcular su análisis.</p>
                </div>
            @endif
        </div>

        <div class="caja-equipo full-width equipo-detalle-card equipo-detalle-section-title">
            <div class="titulo-equipo">
                <i class="fas fa-users"></i>
                <div>
                    <h3>Plantilla</h3>
                    <p>{{ $equipo->jugadores_count }} jugadores registrados</p>
                </div>
            </div>
        </div>

        <div class="rejilla-jugadores equipo-detalle-rejilla">
            @forelse($equipo->jugadores as $jugador)
                <article class="tarjeta-jugador">
                    <div class="foto-jugador foto-jugador--player">
                        <img src="{{ $jugador->image_url }}" alt="{{ $jugador->nombre }} {{ $jugador->apellido }}">
                    </div>

                    <div class="info-jugador">
                        <p class="posicion-tag">{{ $jugador->posicion_nombre }}</p>
                        <h2>{{ $jugador->nombre }} {{ $jugador->apellido }}</h2>
                        <p><strong>Dorsal:</strong> #{{ $jugador->dorsal ?? '00' }}</p>
                        <p><strong>Equipo:</strong> {{ $equipo->nombre }}</p>
                    </div>
                </article>
            @empty
                <div class="contenedor-mensaje-vacio" style="grid-column: 1 / -1;">
                    <div class="alerta-basket">
                        <p>Este equipo local todavía no tiene jugadores registrados.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    <div class="caja-equipo full-width equipo-detalle-card equipo-detalle-partidos">
        <div class="titulo-equipo">
            <i class="fas fa-calendar-alt"></i>
            <div>
                <h3>Partidos relacionados</h3>
                <p>{{ $partidos->count() }} partidos encontrados</p>
            </div>
        </div>

        @forelse($partidos->take(5) as $partido)
            <div class="fila-detalle equipo-partido-row">
                <span>{{ $partido->equipoLocal?->nombre ?? $partido->equipo_local }} vs {{ $partido->equipoVisitante?->nombre ?? $partido->equipo_visitante }}</span>
                <div class="valor">
                    <strong>{{ $partido->resultado }}</strong>
                    <small>{{ $partido->fecha_partido->format('d/m/Y H:i') }}</small>
                    <a href="{{ route('basket.partidos.show', $partido) }}" class="btn-public btn-public--secondary btn-public--sm">Ver detalle</a>
                </div>
            </div>
        @empty
            <div class="alerta-basket" style="margin-top: 18px;">
                <p>No hay partidos registrados para este equipo.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
