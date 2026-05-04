@extends('layouts.app')

@section('title', 'Equipos')

@section('contenido')
<section class="seccion-equipo-header">
    <h1>Equipos</h1>
    <p>Descubre todos los equipos del club y filtra por categoría.</p>
</section>

<section class="navegacion-categorias">
    <div class="botones-categoria">
        <a href="{{ route('basket.equipos') }}" class="boton-categoria {{ empty($selectedCategory) ? 'activo' : '' }}">
            Todas
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('basket.equipos', ['category' => $category->id]) }}"
               class="boton-categoria {{ (int) $selectedCategory === (int) $category->id ? 'activo' : '' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</section>

<section class="equipo-jugadores">
    <div class="rejilla-jugadores">
        @foreach ($equipos as $equipo)
            <article class="tarjeta-jugador">
                <div class="foto-jugador">
                    @if($equipo->image)
                        <img src="{{ $equipo->image_url }}" alt="{{ $equipo->nombre }}">
                    @else
                        <img src="{{ asset('img/basket.jpeg') }}" alt="Sin imagen disponible" class="foto-defecto">
                    @endif
                </div>

                <div class="info-jugador">
                    <p class="posicion-tag">{{ $equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría' }}</p>
                    <h2>{{ $equipo->nombre ?? $equipo->name }}</h2>
                    <p><strong>Categoría:</strong> {{ $equipo->category?->name ?? $equipo->categoria ?? 'Sin categoría' }}</p>

                    @if(!empty($equipo->descripcion ?? $equipo->description))
                        <div class="separador"></div>
                        <p>{{ $equipo->descripcion ?? $equipo->description }}</p>
                    @else
                        <div class="separador"></div>
                        <p>Equipo del Bellreguard Club de Basket.</p>
                    @endif
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
