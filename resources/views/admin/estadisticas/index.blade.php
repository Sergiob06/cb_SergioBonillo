@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Análisis de Equipos</h2>
        <p style="color: #777;">Las estadísticas se calculan desde los partidos de equipos locales</p>
    </div>
</header>

<div class="contenedor-buscador">
    <form action="{{ route('estadisticas.index') }}" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text"
                   name="search"
                   placeholder="Buscar equipo local o categoría..."
                   value="{{ $search ?? '' }}"
                   class="input-search">
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>

        @if(($search ?? '') !== '')
            <a href="{{ route('estadisticas.index') }}" class="btn-limpiar" title="Limpiar búsqueda">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </form>
</div>

<div class="pizarra-admin">
    <div class="tabla-admin-wrapper">
        <table class="tabla-admin tabla-admin-listado">
            <thead>
                <tr>
                    <th>Equipo local</th>
                    <th>Categoría</th>
                    <th>Partidos jugados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipos as $equipo)
                    <tr>
                        <td data-label="Equipo local" class="tabla-admin-principal"><strong>{{ $equipo->nombre }}</strong></td>
                        <td data-label="Categoría">{{ $equipo->category?->name ?? $equipo->categoria }}</td>
                        <td data-label="Partidos jugados">{{ $equipo->partidos_jugados_count }}</td>
                        <td data-label="Acciones" class="tabla-admin-celda-acciones">
                            <div class="tabla-admin-acciones">
                                <a href="{{ route('equipos.analisis', $equipo) }}" class="btn-accion" title="Ver análisis" style="background-color: #6f42c1; color: white;">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="{{ route('partidos.index', ['search' => $equipo->nombre]) }}" class="btn-accion" title="Ver partidos" style="background-color: #00b4d8; color: white;">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="tabla-admin-vacia">No hay equipos locales para analizar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="contenedor-paginacion">
        {{ $equipos->links() }}
    </div>
</div>
@endsection
