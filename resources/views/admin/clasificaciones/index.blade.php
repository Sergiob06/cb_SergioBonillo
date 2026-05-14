@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Clasificación</h2>
        <p style="color: #777;">Tabla de posiciones por categoría y temporada</p>
    </div>

    <a href="{{ route('clasificaciones.create') }}" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nueva Fila
    </a>
</header>

<div class="contenedor-buscador">
    <form action="{{ route('clasificaciones.search') }}" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text" name="search" placeholder="Buscar por equipo, categoría o temporada..." value="{{ $search ?? '' }}" class="input-search">
            <button type="submit" class="btn-buscar"><i class="fas fa-search"></i></button>
        </div>
        @if(isset($search) && $search != '')
            <a href="{{ route('clasificaciones.index') }}" class="btn-limpiar" title="Limpiar búsqueda"><i class="fas fa-times"></i></a>
        @endif
    </form>
</div>

@if(session('mensaje'))
    <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> {{ session('mensaje') }}
    </div>
@endif

<div class="pizarra-admin">
    <div class="tabla-admin-wrapper">
    <table class="tabla-admin tabla-admin-listado">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Equipo</th>
                <th>Categoría</th>
                <th>Temporada</th>
                <th>PJ</th>
                <th>PG</th>
                <th>PP</th>
                <th>Pts</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clasificaciones as $clasificacion)
                <tr>
                    <td data-label="Pos"><strong>{{ $clasificacion->posicion }}</strong></td>
                    <td data-label="Equipo" class="tabla-admin-principal">{{ $clasificacion->equipo_nombre }}</td>
                    <td data-label="Categoría">{{ $clasificacion->categoria }}</td>
                    <td data-label="Temporada">{{ $clasificacion->temporada }}</td>
                    <td data-label="PJ">{{ $clasificacion->partidos_jugados }}</td>
                    <td data-label="PG">{{ $clasificacion->partidos_ganados }}</td>
                    <td data-label="PP">{{ $clasificacion->partidos_perdidos }}</td>
                    <td data-label="Pts"><strong>{{ $clasificacion->puntos }}</strong></td>
                    <td data-label="Acciones" class="tabla-admin-celda-acciones">
                        <div class="tabla-admin-acciones">
                            <a href="{{ route('clasificaciones.show', $clasificacion->id) }}" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('clasificaciones.edit', $clasificacion->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('clasificaciones.destroy', $clasificacion->id) }}" method="POST" onsubmit="return confirm('¿Eliminar fila de clasificación?')" style="margin: 0; display: flex; align-items: center;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="tabla-admin-vacia">Todavía no hay filas de clasificación registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="contenedor-paginacion">
        {{ $clasificaciones->links() }}
    </div>
</div>
@endsection
