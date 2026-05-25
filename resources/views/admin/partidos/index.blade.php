@extends('layouts.admin')

@section('contenido_admin')
@php($esAdmin = auth()->user()?->rol === 'admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Partidos</h2>
        <p style="color: #777;">Calendario de encuentros del Bellreguard CB</p>
    </div>

    @if($esAdmin)
        <a href="{{ route('partidos.create') }}" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nuevo Partido
        </a>
    @endif
</header>

<div class="contenedor-buscador admin-partidos-filter-wrap">
    <form action="{{ route('partidos.index') }}" method="GET" class="form-buscador admin-partidos-filter-form">
        <div class="input-grupal admin-partidos-search-group">
            <input type="text"
                   name="search"
                   placeholder="Buscar por equipo o lugar..."
                   value="{{ $search ?? '' }}"
                   class="input-search admin-partidos-search-input">
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <select name="categoria" class="input-search admin-partidos-filter-select admin-partidos-filter-select--categoria" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach(($categories ?? collect()) as $category)
                <option value="{{ $category->id }}" {{ (int) ($categoriaSeleccionada ?? 0) === (int) $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="estado" class="input-search admin-partidos-filter-select admin-partidos-filter-select--estado" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="proximo" {{ ($estadoSeleccionado ?? '') === 'proximo' ? 'selected' : '' }}>Próximo</option>
            <option value="jugado" {{ ($estadoSeleccionado ?? '') === 'jugado' ? 'selected' : '' }}>Jugado</option>
        </select>

        @if((isset($search) && $search != '') || !empty($categoriaSeleccionada) || !empty($estadoSeleccionado))
            <a href="{{ route('partidos.index') }}" class="btn-limpiar" title="Limpiar búsqueda">
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

@if(session('mensaje_error'))
    <div style="padding: 15px; background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 5px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('mensaje_error') }}
    </div>
@endif

<div class="pizarra-admin">
    <div class="tabla-admin-wrapper">
    <table class="tabla-admin tabla-admin-listado">
        <thead>
            <tr>
                <th>Local</th>
                <th>Visitante</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Resultado</th>
                <th>Lugar</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($partidos as $partido)
                <tr>
                    <td data-label="Local" class="tabla-admin-principal">{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }}</td>
                    <td data-label="Visitante">{{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</td>
                    <td data-label="Categoría">{{ $partido->category?->name ?? $partido->equipoLocal?->category?->name ?? '-' }}</td>
                    <td data-label="Estado"><span class="estado-partido estado-partido--{{ $partido->estado }}">{{ $partido->estado_nombre }}</span></td>
                    <td data-label="Fecha">{{ $partido->fecha_partido->format('d/m/Y H:i') }}</td>
                    <td data-label="Resultado">{{ $partido->resultado }}</td>
                    <td data-label="Lugar">{{ $partido->lugar }}</td>
                    <td data-label="Acciones" class="tabla-admin-celda-acciones">
                        <div class="tabla-admin-acciones">
                            <a href="{{ route('partidos.show', $partido->id) }}" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($esAdmin)
                                <a href="{{ route('partidos.edit', $partido->id) }}" class="btn-accion editar" title="Editar partido" style="margin: 0;">
                                    <i class="fas fa-pen"></i>
                                </a>
                            @endif

                            @if($partido->es_jugado)
                                <a href="{{ route('partidos.estadisticas.edit', $partido->id) }}" class="btn-accion" title="{{ $partido->tiene_estadisticas_equipo ? 'Editar estadísticas' : 'Añadir estadísticas' }}" style="background-color: #023e8a; color: white;">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                            @else
                                <span class="btn-accion" title="Las estadísticas solo pueden añadirse cuando el partido ya ha sido jugado." style="background-color: #bbb; color: white; cursor: not-allowed;">
                                    <i class="fas fa-chart-bar"></i>
                                </span>
                            @endif

                            @if($esAdmin)
                                <form action="{{ route('partidos.destroy', $partido->id) }}" method="POST" onsubmit="return confirm('¿Eliminar partido?')" style="margin: 0; display: flex; align-items: center;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="tabla-admin-vacia">Todavía no hay partidos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="contenedor-paginacion">
        {{ $partidos->links() }}
    </div>
</div>
@endsection
