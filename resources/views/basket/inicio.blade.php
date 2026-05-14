{{-- LAYOUT PRINCIPAL --}}
@extends('layouts.app')

{{-- TÍTULO DE LA PESTAÑA --}}
@section('title', 'Inicio - Bellreguard Club de Basket')

{{-- CONTENIDO DE LA PÁGINA --}}
@section('contenido')

    <section class="seccion-hero">
        <div class="hero-texto">
            <h1>Bellreguard Club de Basket</h1>
           <p style="background:yellow;padding:10px;">
DEPLOY AUTOMÁTICO FUNCIONANDO
</p>
 <p>Pasión, dedicación y excelencia en cada partido. Únete a nuestra familia basketbolística.</p>
            <div class="botones-hero">
                <a href="{{ route('basket.partidos') }}" class="boton-principal" style="text-decoration: none;">Ver Próximos Partidos</a>
                <a href="{{ route('basket.equipos') }}" class="boton-secundario" style="text-decoration: none;">Ver Equipos</a>
            </div>
        </div>
        <div class="hero-imagen">
            <img src="{{ asset('img/basket.png') }}" alt="Equipo celebrando" />
        </div>
    </section>

    <section class="home-section">
        <div class="home-section-shell">
            <div class="home-section-box">
                <div class="home-section-header">
                    <h2>Próximos Partidos</h2>
                    <p class="subtitulo">Información cargada directamente desde la base de datos</p>
                </div>

                <div class="home-card-grid home-grid">
                    @forelse ($proximosPartidos as $partido)
                        <div class="home-card">
                            <div class="home-card-content">
                                <span class="fecha">{{ $partido->fecha_partido->translatedFormat('d F Y - H:i') }}</span>
                                <h3>{{ $partido->equipo_local }} vs {{ $partido->equipo_visitante }}</h3>
                                <p>{{ $partido->lugar }}</p>
                                <a href="{{ route('basket.partidos') }}">Ver calendario →</a>
                            </div>
                        </div>
                    @empty
                        <div class="home-card">
                            <div class="home-card-content">
                                <span class="fecha">Agenda vacía</span>
                                <h3>No hay partidos programados</h3>
                                <p>Cuando el admin añada partidos, aparecerán aquí automáticamente.</p>
                                <a href="{{ route('basket.partidos') }}">Ir a partidos →</a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="home-section-shell">
            <div class="home-section-box tabla-contenedor home-table-box">
                <div class="home-section-header home-section-header-table">
                    <h2>Clasificación</h2>
                    <p class="subtitulo">
                        @if($categoriaClasificacion || $temporadaClasificacion)
                            {{ $categoriaClasificacion ?? 'Categoría' }}{{ $temporadaClasificacion ? ' · ' . $temporadaClasificacion : '' }}
                        @else
                            Posición actual de los equipos del club
                        @endif
                    </p>
                </div>

                @if($clasificacion->isNotEmpty())
                    <div class="tabla-scroll">
                        <table class="tabla-clasificacion">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Equipo</th>
                                    <th>PJ</th>
                                    <th>V</th>
                                    <th>D</th>
                                    <th>Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clasificacion as $fila)
                                    <tr class="{{ $fila->equipo_id ? 'fila-destacada' : '' }}">
                                        <td>{{ $fila->posicion }}</td>
                                        <td>{{ $fila->equipo_nombre }}</td>
                                        <td>{{ $fila->partidos_jugados }}</td>
                                        <td>{{ $fila->partidos_ganados }}</td>
                                        <td>{{ $fila->partidos_perdidos }}</td>
                                        <td><strong>{{ $fila->puntos }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="home-empty-state">No hay datos de clasificación disponibles</div>
                @endif
            </div>
        </div>
    </section>

    @if($ultimasFotos->isNotEmpty())
        <section class="home-section">
            <div class="home-section-shell">
                <div class="home-section-box">
                    <div class="home-section-header">
                        <h2>Últimas Fotos</h2>
                        <p class="subtitulo">Contenido real de la galería del club</p>
                    </div>

                    <div class="home-card-grid home-grid">
                        @foreach ($ultimasFotos as $foto)
                            <div class="home-card">
                                <img src="{{ $foto->image_url }}" alt="{{ $foto->titulo }}" />
                                <div class="home-card-content">
                                    <span class="fecha">{{ $foto->fecha_imagen ? $foto->fecha_imagen->translatedFormat('d F Y') : 'Sin fecha' }}</span>
                                    <h3>{{ $foto->titulo }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($foto->descripcion, 90) }}</p>
                                    <a href="{{ route('basket.galeria') }}">Ver galería →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
