@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Estadísticas</h2>
        <p style="color: #777;">Resumen deportivo por equipo y temporada</p>
    </div>

    <a href="{{ route('estadisticas.create') }}" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nueva Estadística
    </a>
</header>

<div class="contenedor-buscador">
    <form action="{{ route('estadisticas.search') }}" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text"
                   name="search"
                   placeholder="Buscar por equipo o temporada..."
                   value="{{ $search ?? '' }}"
                   class="input-search">
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>

        @if(isset($search) && $search != '')
            <a href="{{ route('estadisticas.index') }}" class="btn-limpiar" title="Limpiar búsqueda">
                <i class="fas fa-times"></i>
            </a>
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
                <th>Equipo</th>
                <th>Temporada</th>
                <th>Puntos</th>
                <th>Rebotes</th>
                <th>Asistencias</th>
                <th>Balance</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estadisticas as $estadistica)
                <tr>
                    <td data-label="Equipo" class="tabla-admin-principal">
                        <strong>{{ $estadistica->equipo->nombre ?? 'Sin equipo' }}</strong><br>
                        <span style="color: #777; font-size: 0.9em;">{{ $estadistica->equipo->categoria ?? 'Sin categoría' }}</span>
                    </td>
                    <td data-label="Temporada">{{ $estadistica->temporada }}</td>
                    <td data-label="Puntos">{{ $estadistica->puntos_totales }}</td>
                    <td data-label="Rebotes">{{ $estadistica->rebotes }}</td>
                    <td data-label="Asistencias">{{ $estadistica->asistencias }}</td>
                    <td data-label="Balance">{{ $estadistica->victorias }}V - {{ $estadistica->derrotas }}D</td>
                    <td data-label="Acciones" class="tabla-admin-celda-acciones">
                        <div class="tabla-admin-acciones">
                            <a href="{{ route('estadisticas.show', $estadistica->id) }}" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('estadisticas.edit', $estadistica->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route('estadisticas.destroy', $estadistica->id) }}" method="POST" onsubmit="return confirm('¿Eliminar estadística?')" style="margin: 0; display: flex; align-items: center;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="tabla-admin-vacia">Todavía no hay estadísticas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="contenedor-paginacion">
        {{ $estadisticas->links() }}
    </div>
</div>
@endsection
