@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Solicitudes de {{ $product->name }}</h2>
        <p style="color: #777;">{{ $conteos['total'] }} solicitudes · {{ $conteos['pendientes'] }} pendientes</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('productos.show', $product) }}" class="btn-nuevo" style="background-color: #00b4d8;"><i class="fas fa-eye"></i> Ver producto</a>
        <a href="{{ route('productos.index') }}" class="btn-nuevo" style="background-color: #777;"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>
</header>

@if(session('mensaje'))
    <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> {{ session('mensaje') }}
    </div>
@endif

<div class="contenedor-buscador">
    <form action="{{ route('productos.solicitudes.index', $product) }}" method="GET" class="form-buscador">
        <select name="estado" class="input-search" style="max-width: 230px;" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach(\App\Models\ProductoSolicitud::ESTADOS as $estado)
                <option value="{{ $estado }}" {{ $estadoSeleccionado === $estado ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $estado)) }}
                </option>
            @endforeach
        </select>

        @if($estadoSeleccionado)
            <a href="{{ route('productos.solicitudes.index', $product) }}" class="btn-limpiar" title="Limpiar filtro"><i class="fas fa-times"></i></a>
        @endif
    </form>
</div>

<div class="pizarra-admin">
    <div class="tabla-admin-wrapper">
        <table class="tabla-admin tabla-admin-listado">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitudes as $solicitud)
                    <tr>
                        <td data-label="Cliente" class="tabla-admin-principal"><strong>{{ $solicitud->nombre }}</strong></td>
                        <td data-label="Email">{{ $solicitud->email }}</td>
                        <td data-label="Telefono">{{ $solicitud->telefono ?: '-' }}</td>
                        <td data-label="Mensaje">{{ \Illuminate\Support\Str::limit($solicitud->mensaje, 90) ?: 'Sin mensaje' }}</td>
                        <td data-label="Estado">
                            <form action="{{ route('productos.solicitudes.estado', $solicitud) }}" method="POST" class="solicitud-estado-inline">
                                @csrf
                                @method('PATCH')
                                <span class="solicitud-estado solicitud-estado--{{ $solicitud->estado }}">{{ $solicitud->estado_nombre }}</span>
                                <select name="estado" class="input-search" onchange="this.form.submit()" aria-label="Cambiar estado de la solicitud de {{ $solicitud->nombre }}">
                                    @foreach(\App\Models\ProductoSolicitud::ESTADOS as $estado)
                                        <option value="{{ $estado }}" {{ $solicitud->estado === $estado ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $estado)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td data-label="Fecha">{{ $solicitud->created_at?->format('d/m/Y H:i') }}</td>
                        <td data-label="Acciones" class="tabla-admin-celda-acciones">
                            <div class="tabla-admin-acciones">
                                <a href="{{ route('productos.solicitudes.show', $solicitud) }}" class="btn-accion" title="Ver solicitud" style="background-color: #00b4d8; color: white;"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="tabla-admin-vacia">No hay solicitudes para este producto.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="contenedor-paginacion">
        {{ $solicitudes->links() }}
    </div>
</div>
@endsection
