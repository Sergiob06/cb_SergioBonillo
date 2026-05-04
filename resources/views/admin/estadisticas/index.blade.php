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
    <table class="tabla-admin">
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
                    <td>
                        <strong>{{ $estadistica->equipo->nombre ?? 'Sin equipo' }}</strong><br>
                        <span style="color: #777; font-size: 0.9em;">{{ $estadistica->equipo->categoria ?? 'Sin categoría' }}</span>
                    </td>
                    <td>{{ $estadistica->temporada }}</td>
                    <td>{{ $estadistica->puntos_totales }}</td>
                    <td>{{ $estadistica->rebotes }}</td>
                    <td>{{ $estadistica->asistencias }}</td>
                    <td>{{ $estadistica->victorias }}V - {{ $estadistica->derrotas }}D</td>
                    <td style="padding: 25px 15px; vertical-align: middle;">
                        <div style="display: flex; gap: 8px; align-items: center; justify-content: flex-start;">
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
                    <td colspan="7" style="text-align: center; padding: 30px; color: #777;">Todavía no hay estadísticas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="contenedor-paginacion">
        {{ $estadisticas->links() }}
    </div>
</div>
@endsection
