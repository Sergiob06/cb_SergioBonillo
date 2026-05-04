{{-- LAYOUT PRINCIPAL --}}
@extends('layouts.app')

{{-- TÍTULO DE LA PESTAÑA --}}
@section('title', 'Inicio - Bellreguard Club de Basket')

{{-- CONTENIDO DE LA PÁGINA --}}
@section('contenido')

<section class="seccion-partidos-header">
    <div class="header-contenido">
        <div class="header-texto">
            <h1>Próximos Partidos</h1>
            <p>Consulta el calendario real de todos nuestros equipos</p>
        </div>
    </div>
</section>

<section class="seccion-calendario">
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
                        <p>{{ $equipoClub?->category?->name ?? $equipoClub?->categoria ?? 'Calendario del equipo' }}</p>
                    </div>
                </div>

                <div class="rejilla-partido-doble">
                    @foreach($partidosEquipo as $partido)
                        @php
                            $nombreLocal = $partido->equipoLocal->nombre ?? $partido->equipo_local;
                            $nombreVisitante = $partido->equipoVisitante->nombre ?? $partido->equipo_visitante;
                            $logoLocal = $partido->equipoLocal && $partido->equipoLocal->imagen_club
                                ? $partido->equipoLocal->image_url
                                : ($equipoClub && $nombreLocal === $equipoClub->nombre && $equipoClub->imagen_club
                                ? $equipoClub->image_url
                                : asset('img/basket.jpeg'));
                            $logoVisitante = $partido->equipoVisitante && $partido->equipoVisitante->imagen_club
                                ? $partido->equipoVisitante->image_url
                                : ($equipoClub && $nombreVisitante === $equipoClub->nombre && $equipoClub->imagen_club
                                ? $equipoClub->image_url
                                : asset('img/basket.jpeg'));
                        @endphp

                        <div class="tarjeta-partido">
                            <span class="etiqueta-proximo activo">Próximo</span>
                            <div class="enfrentamiento">
                                <div class="equipo local">
                                    <img src="{{ $logoLocal }}" alt="{{ $nombreLocal }}">
                                    <p>{{ $nombreLocal }}</p>
                                </div>

                                <span class="vs">VS</span>

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
                            </div>
                        </div>
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
