@extends('layouts.app')

@section('title', 'Estadísticas - Bellreguard Club de Basket')

@section('contenido')
<section class="seccion-estadisticas-header">
    <div class="header-contenido">
        <h1>Estadísticas de Partidos</h1>
        <p>Resumen sencillo de los partidos jugados por equipos locales</p>
    </div>
</section>

<section class="contenedor-estadisticas">
    <form action="{{ route('basket.estadisticas') }}" method="GET" class="public-filters public-filters-form">
        <div class="public-filter-group public-filter-group--search public-search-input">
            <input type="text"
                   name="search"
                   placeholder="Buscar por partido, equipo o categoría..."
                   value="{{ $search ?? '' }}"
                   class="public-filter-control">
            <button type="submit" class="public-search-button" aria-label="Buscar estadísticas">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <select name="equipo" class="public-filter-control public-filter-select" aria-label="Filtrar por equipo">
            <option value="">Todos los equipos locales</option>
            @foreach($equiposLocales as $equipo)
                <option value="{{ $equipo->id }}" {{ (int) $equipoSeleccionado === (int) $equipo->id ? 'selected' : '' }}>
                    {{ $equipo->nombre }}
                </option>
            @endforeach
        </select>

        <select name="categoria" class="public-filter-control public-filter-select" aria-label="Filtrar por categoría">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (int) $categoriaSeleccionada === (int) $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-public btn-public--primary public-filter-button">Filtrar</button>

        @if(($search ?? '') !== '' || $equipoSeleccionado || $categoriaSeleccionada)
            <a href="{{ route('basket.estadisticas') }}" class="btn-public btn-public--secondary public-filter-button">Limpiar</a>
        @endif
    </form>

    @forelse($partidos as $partido)
        @php
            $nombreLocal = $partido?->equipoLocal?->nombre ?? $partido?->equipo_local ?? 'Sin local';
            $nombreVisitante = $partido?->equipoVisitante?->nombre ?? $partido?->equipo_visitante ?? 'Sin visitante';
            $estadisticaBellreguard = $partido->estadisticasEquipos->first(fn ($estadistica) => $estadistica->equipo?->es_local);
            $estadisticaRival = $estadisticaBellreguard?->estadisticaRival();
            $diferencia = $estadisticaBellreguard && $estadisticaRival
                ? $estadisticaBellreguard->puntos_anotados - $estadisticaRival->puntos_anotados
                : null;
        @endphp

        <article class="caja-detalle estadistica-card">
            <div class="detalle-header">
                <i class="fas fa-chart-line icon-red"></i>
                <div>
                    <h4>{{ $nombreLocal }} vs {{ $nombreVisitante }}</h4>
                    <p class="estadistica-card-meta">
                        {{ $partido?->fecha_partido?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                        · {{ $partido?->lugar ?? 'Sin lugar' }}
                        · Resultado: {{ $partido?->resultado ?? 'Pendiente' }}
                        · Estadísticas: {{ $estadisticaBellreguard?->equipo?->nombre ?? 'Bellreguard' }}
                    </p>
                </div>
            </div>

            <div class="rejilla-stats-top estadistica-card-stats">
                <div class="card-stat"><div class="stat-info"><h3>{{ $estadisticaBellreguard?->puntos_anotados ?? '-' }}</h3><p>Puntos anotados</p></div></div>
                <div class="card-stat"><div class="stat-info"><h3>{{ $estadisticaRival?->puntos_anotados ?? '-' }}</h3><p>Puntos recibidos</p></div></div>
                <div class="card-stat"><div class="stat-info"><h3>{{ $diferencia ?? '-' }}</h3><p>Diferencia</p></div></div>
                <div class="card-stat"><div class="stat-info"><h3>{{ $estadisticaBellreguard?->rebotes_totales ?? '-' }}</h3><p>Rebotes</p></div></div>
            </div>

            <div class="estadistica-card-actions">
                <a href="{{ route('basket.partidos.show', $partido) }}" class="btn-public btn-public--primary">
                    <i class="fas fa-arrow-right"></i>
                    Ver partido
                </a>
            </div>
        </article>
    @empty
        <div class="caja-equipo full-width">
            <div class="titulo-equipo">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Sin estadísticas disponibles</h3>
                    <p>No hay partidos con estadísticas para los filtros seleccionados.</p>
                </div>
            </div>
        </div>
    @endforelse
</section>
@endsection
