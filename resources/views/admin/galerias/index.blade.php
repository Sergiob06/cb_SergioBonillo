@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Galería</h2>
        <p style="color: #777;">Fotos y descripciones de la temporada</p>
    </div>

    <a href="{{ route('galerias.create') }}" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nueva Foto
    </a>
</header>

<div class="contenedor-buscador">
    <form action="{{ route('galerias.search') }}" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text" name="search" placeholder="Buscar por título o descripción..." value="{{ $search ?? '' }}" class="input-search">
            <button type="submit" class="btn-buscar"><i class="fas fa-search"></i></button>
        </div>
        @if(isset($search) && $search != '')
            <a href="{{ route('galerias.index') }}" class="btn-limpiar" title="Limpiar búsqueda"><i class="fas fa-times"></i></a>
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
                <th>Imagen</th>
                <th>Título</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($galerias as $galeria)
                <tr>
                    <td data-label="Imagen" class="tabla-admin-imagen">
                        <div class="admin-table-media admin-table-media--wide admin-table-media--photo">
                            <img src="{{ $galeria->image_url }}" alt="{{ $galeria->titulo }}">
                        </div>
                    </td>
                    <td data-label="Título" class="tabla-admin-principal"><strong>{{ $galeria->titulo }}</strong></td>
                    <td data-label="Fecha">{{ $galeria->fecha_imagen ? $galeria->fecha_imagen->format('d/m/Y') : '-' }}</td>
                    <td data-label="Descripción">{{ \Illuminate\Support\Str::limit($galeria->descripcion, 80) }}</td>
                    <td data-label="Acciones" class="tabla-admin-celda-acciones">
                        <div class="tabla-admin-acciones">
                            <a href="{{ route('galerias.show', $galeria->id) }}" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('galerias.edit', $galeria->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('galerias.destroy', $galeria->id) }}" method="POST" onsubmit="return confirm('¿Eliminar foto?')" style="margin: 0; display: flex; align-items: center;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="tabla-admin-vacia">Todavía no hay fotos registradas en la galería.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="contenedor-paginacion">
        {{ $galerias->links() }}
    </div>
</div>
@endsection
