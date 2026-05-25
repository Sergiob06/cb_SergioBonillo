@extends('layouts.admin')

@section('contenido_admin')
@php($esAdmin = auth()->user()?->rol === 'admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Jugadores</h2>
        <p style="color: #777;">Plantilla completa del club</p>
    </div>
    
    @if($esAdmin)
        <a href="{{ route('jugadores.create') }}" class="btn-nuevo">
            <i class="fas fa-plus"></i> Añadir Jugador
        </a>
    @endif
</header>


{{-- Buscador y filtro de equipos locales --}}
<div class="contenedor-buscador">
    <form action="{{ route('jugadores.index') }}" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text" 
                   name="search" 
                   placeholder="Buscar por nombre..." 
                   value="{{ $search ?? '' }}" {{-- Mantiene el texto después de buscar --}}
                   class="input-search">
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <select name="equipo_id" class="input-search" style="max-width: 260px;" onchange="this.form.submit()">
            <option value="">Todos los equipos locales</option>
            @foreach(($equiposLocales ?? collect()) as $equipo)
                <option value="{{ $equipo->id }}" {{ (int) ($equipoSeleccionado ?? 0) === (int) $equipo->id ? 'selected' : '' }}>
                    {{ $equipo->nombre }}
                </option>
            @endforeach
        </select>
        {{-- Si hay una búsqueda activa, mostramos un botón para limpiar --}}
        @if((isset($search) && $search != '') || !empty($equipoSeleccionado))
            <a href="{{ route('jugadores.index') }}" class="btn-limpiar" title="Limpiar búsqueda">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </form>
</div>

{{-- Mensaje de éxito al crear, editar o borrar --}}
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
                <th>Foto</th>
                <th>Dorsal</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Club</th>
                <th>Categoría</th>
                <th>Posición</th>
                <th>Fecha Nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jugadores as $jugador)
            <tr>
                {{-- 1. FOTO --}}
                <td data-label="Foto" class="tabla-admin-imagen">
                    <div class="admin-table-media admin-table-media--photo">
                        <img src="{{ $jugador->image_url }}" alt="Foto de {{ $jugador->nombre }} {{ $jugador->apellido }}">
                    </div>
                </td>
                {{-- 2. DORSAL --}}
                <td data-label="Dorsal">
                    <span class="tabla-admin-dorsal">{{ $jugador->dorsal !== null ? '#' . $jugador->dorsal : '-' }}</span>
                </td>            
                {{-- 3. NOMBRE --}}
                <td data-label="Nombre" class="tabla-admin-principal"><strong>{{ $jugador->nombre }}</strong></td>

                {{-- 3.APELLIDO --}}
                <td data-label="Apellidos"><strong>{{ $jugador->apellido }}</strong></td>
                
                {{-- 4. CLUB --}}
                <td data-label="Club">
                    {{ $jugador->equipo->nombre ?? 'Sin Equipo' }}
                </td>

                {{-- 5. CATEGORÍA (Sacada del modelo Equipo) --}}
                <td data-label="Categoría">
                    <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold;">
                        {{ $jugador->equipo->category?->name ?? $jugador->equipo->categoria ?? '-' }}
                    </span>
                </td>

                {{-- 6. POSICIÓN --}}
                <td data-label="Posición">{{ $jugador->posicion_nombre }}</td>

                {{-- 4. FECHA DE NACIMIENTO --}}
                <td data-label="Fecha Nacimiento">
                    {{-- Usamos carbon para poner la fecha en formato día/mes/año --}}
                    {{ $jugador->fecha_nacimiento ? \Carbon\Carbon::parse($jugador->fecha_nacimiento)->format('d/m/Y') : 'No asignada' }}
                </td>
                
                {{-- 7. ACCIONES (ALINEADAS) --}}
                <td data-label="Acciones" class="tabla-admin-celda-acciones">
                    <div class="tabla-admin-acciones">
                        
                        {{-- Botón Ver --}}
                        <a href="{{ route('jugadores.show', $jugador->id) }}" class="btn-accion" style="background: #e3f2fd; color: #1976d2;" title="Ver Detalles">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if($esAdmin)
                            <a href="{{ route ('jugadores.edit', $jugador->id)}}" class="btn-accion editar" title="Editar" style="margin: 0;">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route ('jugadores.destroy', $jugador->id)}}" method="POST" onsubmit="return confirm('¿Eliminar jugador?')" style="margin: 0; display: flex; align-items: center;">
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
                <td colspan="9" class="tabla-admin-vacia">No hay jugadores que coincidan con los filtros aplicados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{--Esto es la paginación--}}
    <div class="contenedor-paginacion">
        {{ $jugadores->links() }}
    </div>

</div>
@endsection
