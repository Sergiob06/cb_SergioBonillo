@extends('layouts.admin') {{-- Carga la base (el diseño general del panel) --}}

@section('contenido_admin') {{-- Aquí empieza el contenido que se mete dentro del diseño --}}
@php($esAdmin = auth()->user()?->rol === 'admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Equipos</h2>
        <p style="color: #777;">Categorías y grupos del Bellreguard CB</p>
    </div>
    @if($esAdmin)
        <a href="{{ route('equipos.create') }}" class="btn-nuevo">
            <i class="fas fa-plus"></i> Nuevo Equipo
        </a>
    @endif
</header>

{{-- Buscador de Equipos --}}
<div class="contenedor-buscador">
    <form action="{{ route('equipos.search') }}" method="GET" class="form-buscador">
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
        <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600; color: #333;">
            <input type="checkbox" name="locales" value="1" {{ !empty($mostrarLocales) ? 'checked' : '' }} onchange="this.form.submit()">
            Solo locales
        </label>
        {{-- Si hay una búsqueda activa, mostramos un botón para limpiar --}}
        @if((isset($search) && $search != '') || !empty($mostrarLocales))
            <a href="{{ route('equipos.index') }}" class="btn-limpiar" title="Limpiar búsqueda">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </form>
</div>


{{-- Mensaje de éxito: Solo aparece cuando creas, editas o borras algo correctamente --}}
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
                <th>Escudo</th>
                <th>Nombre Equipo</th>
                <th>Categoría</th>
                <th>Nº Jugadores</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {{-- BUCLE: Repite la fila por cada equipo que haya en la base de datos --}}
            @foreach($equipos as $equipo)
            <tr>
                <td data-label="Escudo" class="tabla-admin-imagen">
                    <div class="admin-table-media admin-table-media--logo">
                        {{-- Muestra la foto guardada en la carpeta public/escudos --}}
                        <img src="{{ $equipo->image_url }}" alt="Escudo de {{ $equipo->nombre }}">
                    </div> {{-- DIV cerrado correctamente --}}
                </td>
                <td data-label="Nombre Equipo" class="tabla-admin-principal"><strong>{{ $equipo->nombre }}</strong></td>
                <td data-label="Categoría">{{ $equipo->category->name ?? $equipo->categoria }}</td>
                <td data-label="Nº Jugadores">{{ $equipo->jugadores_count }}</td>
                <td data-label="Acciones" class="tabla-admin-celda-acciones">
                    {{-- Contenedor de los botones Editar y Borrar --}}
                    <div class="tabla-admin-acciones">
                        
                        <a href="{{ route('equipos.show', $equipo->id) }}" class="btn-accion" title="Ver Detalle" style="background-color: #00b4d8; color: white;">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if($equipo->es_local)
                            <a href="{{ route('jugadores.index', ['equipo_id' => $equipo->id]) }}" class="btn-accion" title="Ver jugadores" style="background-color: #198754; color: white;">
                                <i class="fas fa-users"></i>
                            </a>
                            <a href="{{ route('equipos.analisis', $equipo) }}" class="btn-accion" title="Análisis del equipo" style="background-color: #6f42c1; color: white;">
                                <i class="fas fa-chart-line"></i>
                            </a>
                        @else
                            <span class="btn-accion btn-accion-deshabilitada" title="Los equipos externos no tienen jugadores gestionables">
                                <i class="fas fa-users-slash"></i>
                            </span>
                        @endif

                        @if($esAdmin)
                            <a href="{{ route('equipos.edit', $equipo->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form action="{{ route('equipos.destroy', $equipo->id) }}" method="POST" onsubmit="return confirm('¿Eliminar equipo?')" style="margin: 0; display: flex; align-items: center;">
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
            @endforeach
        </tbody>
    </table>
    </div>

    {{--Esto es la paginación--}}
    <div class="contenedor-paginacion">
        {{ $equipos->links() }}
    </div>

</div>
@endsection
