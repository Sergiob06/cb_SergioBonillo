@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Gestión de Productos</h2>
        <p style="color: #777;">Merchandising oficial del club</p>
    </div>

    <a href="{{ route('productos.create') }}" class="btn-nuevo">
        <i class="fas fa-plus"></i> Nuevo Producto
    </a>
</header>

<div class="contenedor-buscador">
    <form action="{{ route('productos.search') }}" method="GET" class="form-buscador">
        <div class="input-grupal">
            <input type="text" name="search" placeholder="Buscar por nombre o descripcion..." value="{{ $search ?? '' }}" class="input-search">
            <button type="submit" class="btn-buscar"><i class="fas fa-search"></i></button>
        </div>
        @if(isset($search) && $search != '')
            <a href="{{ route('productos.index') }}" class="btn-limpiar" title="Limpiar búsqueda"><i class="fas fa-times"></i></a>
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
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Solicitudes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr class="{{ $product->solicitudes_pendientes_count > 0 ? 'producto-con-pendientes' : '' }}">
                    <td data-label="Imagen" class="tabla-admin-imagen">
                        <div class="admin-table-media admin-table-media--wide admin-table-media--photo">
                            <img src="{{ $product->image_url ?: asset('img/basket.jpeg') }}" alt="{{ $product->name }}">
                        </div>
                    </td>
                    <td data-label="Producto" class="tabla-admin-principal"><strong>{{ $product->name }}</strong></td>
                    <td data-label="Descripción">{{ \Illuminate\Support\Str::limit($product->description, 80) ?: 'Sin descripcion' }}</td>
                    <td data-label="Precio">{{ number_format((float) $product->price, 2, ',', '.') }} EUR</td>
                    <td data-label="Solicitudes">
                        <a href="{{ route('productos.solicitudes.index', $product) }}" class="admin-solicitudes-link">
                            <span>{{ $product->solicitudes_count }} solicitudes</span>
                            @if($product->solicitudes_pendientes_count > 0)
                                <strong>{{ $product->solicitudes_pendientes_count }} pendientes</strong>
                            @else
                                <small>Sin pendientes</small>
                            @endif
                        </a>
                    </td>
                    <td data-label="Acciones" class="tabla-admin-celda-acciones">
                        <div class="tabla-admin-acciones">
                            <a href="{{ route('productos.show', $product->id) }}" class="btn-accion" title="Ver detalle" style="background-color: #00b4d8; color: white;"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('productos.solicitudes.index', $product) }}" class="btn-accion" title="Ver solicitudes" style="background-color: #f59e0b; color: white;"><i class="fas fa-inbox"></i></a>
                            <a href="{{ route('productos.edit', $product->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('productos.destroy', $product->id) }}" method="POST" onsubmit="return confirm('¿Eliminar producto?')" style="margin: 0; display: flex; align-items: center;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="tabla-admin-vacia">Todavía no hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="contenedor-paginacion">
        {{ $products->links() }}
    </div>
</div>
@endsection
