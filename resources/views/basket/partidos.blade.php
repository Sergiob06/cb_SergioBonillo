{{-- LAYOUT PRINCIPAL --}}
@extends('layouts.app')

{{-- TÍTULO DE LA PESTAÑA --}}
@section('title', 'Inicio - Bellreguard Club de Basket')

{{-- CONTENIDO DE LA PÁGINA --}}
@section('contenido')

<section class="seccion-partidos-header">
    <div class="header-contenido">
        <div class="header-texto">
            <h1>Partidos</h1>
            <p>Consulta calendario, resultados y estadísticas por equipo local</p>
        </div>
    </div>
</section>

<section class="seccion-calendario">
    <form action="{{ route('basket.partidos') }}" method="GET" class="public-filters public-filters-form">
        <div class="public-filter-group public-filter-group--search public-search-input">
            <input type="text"
                   name="search"
                   placeholder="Buscar por equipo, categoría o fecha..."
                   value="{{ $search ?? '' }}"
                   class="public-filter-control">
            <button type="submit" class="public-search-button" aria-label="Buscar partidos">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <select name="equipo" class="public-filter-control public-filter-select" aria-label="Filtrar por equipo local" onchange="this.form.submit()">
            <option value="">Todos los equipos locales</option>
            @foreach($equiposLocales as $equipo)
                <option value="{{ $equipo->id }}" {{ (int) $equipoSeleccionado === (int) $equipo->id ? 'selected' : '' }}>
                    {{ $equipo->nombre }}
                </option>
            @endforeach
        </select>
        <select name="categoria" class="public-filter-control public-filter-select" aria-label="Filtrar por categoría" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (int) $categoriaSeleccionada === (int) $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @if(($search ?? '') !== '' || $equipoSeleccionado || $categoriaSeleccionada)
            <a href="{{ route('basket.partidos') }}" class="btn-public btn-public--secondary public-filter-button">Limpiar filtro</a>
        @endif
    </form>

    <div class="rejilla-partidos">
        @forelse($partidosAgrupados as $nombreEquipo => $partidosEquipo)
            @php
                $equipoClub = $partidosEquipo->first()->equipoLocal ?? $partidosEquipo->first()->equipoVisitante;
            @endphp

            <div class="caja-equipo full-width">
                <div class="titulo-equipo">
                    <i class="fa-solid fa-trophy"></i>
                    <div>
                        <h3>{{ $nombreEquipo }}</h3>
                        <p>{{ $partidosEquipo->first()?->category?->name ?? $equipoClub?->category?->name ?? $equipoClub?->categoria ?? 'Calendario del equipo' }}</p>
                    </div>
                </div>

                <div class="rejilla-partido-doble">
                    @foreach($partidosEquipo as $partido)
                        @php
                            $nombreLocal = $partido->equipoLocal->nombre ?? $partido->equipo_local;
                            $nombreVisitante = $partido->equipoVisitante->nombre ?? $partido->equipo_visitante;
                            $logoLocal = $partido->equipoLocal?->image_url ?? asset(\App\Support\ImagePath::DEFAULT_TEAM_IMAGE);
                            $logoVisitante = $partido->equipoVisitante?->image_url ?? asset(\App\Support\ImagePath::DEFAULT_TEAM_IMAGE);
                        @endphp

                        <a class="tarjeta-partido" href="{{ route('basket.partidos.show', $partido) }}">
                            <span class="etiqueta-proximo estado-partido estado-partido--{{ $partido->estado }}">{{ $partido->estado_nombre }}</span>
                            <div class="enfrentamiento">
                                <div class="equipo local">
                                    <img src="{{ $logoLocal }}" alt="{{ $nombreLocal }}">
                                    <p>{{ $nombreLocal }}</p>
                                </div>

                                <span class="vs">{{ $partido->es_jugado ? $partido->resultado : 'Por jugar' }}</span>

                                <div class="equipo visitante">
                                    <img src="{{ $logoVisitante }}" alt="{{ $nombreVisitante }}">
                                    <p>{{ $nombreVisitante }}</p>
                                </div>
                            </div>

                            <div class="info-adicional">
                                <div class="dato-horario">
                                    <i class="fa-regular fa-calendar-alt"></i>
                                    {{ $partido->fecha_partido->locale('es')->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="dato-horario">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ $partido->fecha_partido->format('H:i') }}h
                                </div>
                                <div class="dato-lugar">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $partido->lugar }}
                                </div>
                                @if($partido->es_jugado && $partido->tiene_estadisticas_equipo)
                                    <div class="dato-horario">
                                        <i class="fa-solid fa-chart-line"></i>
                                        {{ $partido->puntos_anotados }} anotados · {{ $partido->rebotes }} reb · {{ $partido->asistencias }} ast
                                    </div>
                                @elseif(!$partido->es_jugado)
                                    <div class="dato-horario">
                                        <i class="fa-solid fa-hourglass-half"></i>
                                        Estadísticas pendientes
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="caja-equipo full-width">
                <div class="titulo-equipo">
                    <i class="fa-solid fa-calendar-alt"></i>
                    <div>
                        <h3>Sin partidos programados</h3>
                        <p>Cuando el administrador añada encuentros, aparecerán aquí automáticamente.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</section>

@endsection
