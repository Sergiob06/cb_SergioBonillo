{{-- LAYOUT PRINCIPAL --}}
@extends('layouts.app')

{{-- TÍTULO DE LA PESTAÑA --}}
@section('title', 'Inicio - Bellreguard Club de Basket')

{{-- CONTENIDO DE LA PÁGINA --}}
@section('contenido')

<section class="seccion-clasificacion-header">
    <div class="header-contenido">
        <h1>Clasificaciones</h1>
        <p>Consulta la posición de nuestros equipos en todas las categorías</p>
        <div class="icono-trofeo">
            <i class="fas fa-trophy"></i>
        </div>
    </div>
</section>

<section class="contenedor-clasificacion">
    @if($categorias->isNotEmpty() && $clasificacionActual)
        <div class="selector-categorias" style="display: flex; flex-wrap: wrap; gap: 10px;">
            @foreach($categorias as $categoria)
                <a href="{{ route('basket.clasificacion', ['categoria' => $categoria]) }}"
                   class="btn-cat {{ $categoriaSeleccionada === $categoria ? 'activo' : '' }}"
                   style="text-decoration: none; display: inline-block;">
                    {{ $categoria }}
                </a>
            @endforeach
        </div>

        @if($temporadas->isNotEmpty())
            <div class="selector-categorias" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px;">
                @foreach($temporadas as $temporada)
                    <a href="{{ route('basket.clasificacion', ['categoria' => $categoriaSeleccionada, 'temporada' => $temporada]) }}"
                       class="btn-cat {{ $temporadaActual === $temporada ? 'activo' : '' }}"
                       style="text-decoration: none; display: inline-block;">
                        {{ $temporada }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="tabla-contenedor">
            <div class="tabla-header">
                <i class="fas fa-trophy"></i> Liga {{ $categoriaSeleccionada }} - Temporada {{ $temporadaActual }}
            </div>
            <div class="tabla-scroll">
                <table class="tabla-clasificacion">
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Equipo</th>
                            <th>PJ</th>
                            <th>PG</th>
                            <th>PP</th>
                            <th>PF</th>
                            <th>PC</th>
                            <th>Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clasificacionActual as $fila)
                            <tr class="{{ $fila->equipo_id ? 'fila-destacada' : '' }}">
                                <td><strong>{{ $fila->posicion }}</strong></td>
                                <td>{{ $fila->equipo_nombre }}</td>
                                <td>{{ $fila->partidos_jugados }}</td>
                                <td>{{ $fila->partidos_ganados }}</td>
                                <td>{{ $fila->partidos_perdidos }}</td>
                                <td>{{ $fila->puntos_favor }}</td>
                                <td>{{ $fila->puntos_contra }}</td>
                                <td><strong>{{ $fila->puntos }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="tabla-contenedor">
            <div class="tabla-header">
                <i class="fas fa-trophy"></i> Clasificación no disponible
            </div>
            <div style="padding: 30px; color: #777;">Cuando el administrador añada la clasificación, aparecerá aquí automáticamente.</div>
        </div>
    @endif
</section>

@endsection
