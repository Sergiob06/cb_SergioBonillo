@extends('layouts.app')

@section('title', 'Equipos')

@section('contenido')
<section class="seccion-equipo-header">
    <h1>Equipos</h1>
    <p>Descubre todos los equipos del club y filtra por categoría.</p>
</section>

<section class="navegacion-categorias">
    <form action="{{ route('basket.equipos') }}" method="GET" class="public-filters public-filters-form">
        <div class="public-filter-group public-filter-group--search public-search-input">
            <input type="text"
                   name="search"
                   placeholder="Buscar por equipo o categoría..."
                   value="{{ $search ?? '' }}"
                   class="public-filter-control">
            <button type="submit" class="public-search-button" aria-label="Buscar equipos">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <select name="category" class="public-filter-control public-filter-select" aria-label="Filtrar por categoría" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (int) $selectedCategory === (int) $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @if(($search ?? '') !== '' || $selectedCategory)
            <a href="{{ route('basket.equipos') }}" class="btn-public btn-public--secondary public-filter-button">Limpiar filtro</a>
        @endif
    </form>
</section>

<section class="equipo-jugadores">
    <div class="rejilla-jugadores">
        @foreach ($equipos as $equipo)
            <article class="tarjeta-jugador">
                <div class="foto-jugador foto-jugador--logo">
                    <img src="{{ $equipo->image_url }}" alt="{{ $equipo->nombre }}">
                </div>

                <div class="info-jugador">
                    <p class="posicion-tag">{{ $equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría' }}</p>
                    <h2>{{ $equipo->nombre ?? $equipo->name }}</h2>
                    <p><strong>Categoría:</strong> {{ $equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría' }}</p>
                    <p><strong>Plantilla:</strong> {{ $equipo->es_local ? $equipo->jugadores_count . ' jugadores' : 'Equipo externo' }}</p>

                    @if(!empty($equipo->descripcion ?? $equipo->description))
                        <div class="separador"></div>
                        <p>{{ $equipo->descripcion ?? $equipo->description }}</p>
                    @else
                        <div class="separador"></div>
                        <p>Equipo del Bellreguard Club de Basket.</p>
                    @endif

                    <div class="card-public-actions">
                        <a href="{{ route('basket.partidos', ['equipo' => $equipo->id]) }}" class="btn-public btn-public--secondary">
                            Ver partidos
                        </a>
                        @if($equipo->es_local)
                            <a href="{{ route('basket.equipos.show', $equipo) }}" class="btn-public btn-public--primary">
                                Ver plantilla
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach

        @if ($equipos->isEmpty())
            <div class="contenedor-mensaje-vacio" style="grid-column: 1 / -1;">
                <div class="alerta-basket">
                    <p>No hay equipos disponibles para este filtro.</p>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
