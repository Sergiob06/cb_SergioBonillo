@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Partidos</h2>
        <p style="color: #777;">Calendario de encuentros del Bellreguard CB</p>
    </div>

    <a href="{{ route('partidos.create') }}" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nuevo Partido
    </a>
</header>

<div class="contenedor-buscador">
    <form action="{{ route('partidos.search') }}" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text"
                   name="search"
                   placeholder="Buscar por equipo o lugar..."
                   value="{{ $search ?? '' }}"
                   class="input-search">
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>

        @if(isset($search) && $search != '')
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

<div class="pizarra-admin">
    <table class="tabla-admin">
        <thead>
            <tr>
                <th>Local</th>
                <th>Visitante</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($partidos as $partido)
                <tr>
                    <td>{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }}</td>
                    <td>{{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</td>
                    <td>{{ $partido->fecha_partido->format('d/m/Y H:i') }}</td>
                    <td>{{ $partido->lugar }}</td>
                    <td style="padding: 25px 15px; vertical-align: middle;">
                        <div style="display: flex; gap: 8px; align-items: center; justify-content: flex-start;">
                            <a href="{{ route('partidos.show', $partido->id) }}" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('partidos.edit', $partido->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route('partidos.destroy', $partido->id) }}" method="POST" onsubmit="return confirm('¿Eliminar partido?')" style="margin: 0; display: flex; align-items: center;">
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
                    <td colspan="5" style="text-align: center; padding: 30px; color: #777;">Todavía no hay partidos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="contenedor-paginacion">
        {{ $partidos->links() }}
    </div>
</div>
@endsection
