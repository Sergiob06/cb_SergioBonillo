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
            <input type="text" name="search" placeholder="Buscar por título, descripción o categoría..." value="{{ $search ?? '' }}" class="input-search">
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
    <table class="tabla-admin">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($galerias as $galeria)
                <tr>
                    <td><img src="{{ $galeria->image_url }}" alt="{{ $galeria->titulo }}" width="80" height="60" style="object-fit: cover; border-radius: 6px;"></td>
                    <td><strong>{{ $galeria->titulo }}</strong></td>
                    <td>{{ $galeria->categoria }}</td>
                    <td>{{ $galeria->fecha_imagen ? $galeria->fecha_imagen->format('d/m/Y') : '-' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($galeria->descripcion, 80) }}</td>
                    <td style="padding: 25px 15px; vertical-align: middle;">
                        <div style="display: flex; gap: 8px; align-items: center;">
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
                    <td colspan="6" style="text-align: center; padding: 30px; color: #777;">Todavía no hay fotos registradas en la galería.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="contenedor-paginacion">
        {{ $galerias->links() }}
    </div>
</div>
@endsection
