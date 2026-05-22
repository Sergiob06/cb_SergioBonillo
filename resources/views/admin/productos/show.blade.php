@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Detalle de Producto</h2>
        <p style="color: #777;">Vista previa del merchandising del club</p>
    </div>
    <a href="{{ route('productos.index') }}" class="btn-nuevo" style="background-color: #777;"><i class="fas fa-arrow-left"></i> Volver al listado</a>
</header>

<div class="pizarra-admin">
    <div class="admin-detail-layout admin-detail-layout--media">
        <div class="admin-detail-media">
            <img src="{{ $product->image_url ?: asset('img/basket.jpeg') }}" alt="{{ $product->name }}" class="admin-detail-product-img">
        </div>
        <div class="admin-detail-content">
            <h3 style="margin-top: 0;">{{ $product->name }}</h3>
            <p><strong>Precio:</strong> {{ number_format((float) $product->price, 2, ',', '.') }} EUR</p>
            <p><strong>Descripcion:</strong> {{ $product->description ?: 'Sin descripcion disponible.' }}</p>
            <p><strong>Creado:</strong> {{ $product->created_at?->format('d/m/Y') }}</p>
            <p><strong>Solicitudes:</strong> {{ $product->solicitudes_count }} totales · {{ $product->solicitudes_pendientes_count }} pendientes</p>
            <a href="{{ route('productos.solicitudes.index', $product) }}" class="btn-nuevo" style="display: inline-flex; width: fit-content; margin-top: 12px;">
                <i class="fas fa-inbox"></i> Ver solicitudes
            </a>
        </div>
    </div>
</div>

<div class="pizarra-admin">
    <div class="admin-section-header">
        <div>
            <h3>Solicitudes recientes</h3>
            <p>Últimos contactos recibidos para este producto</p>
        </div>
    </div>

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
                @forelse($solicitudesRecientes as $solicitud)
                    <tr>
                        <td data-label="Cliente" class="tabla-admin-principal"><strong>{{ $solicitud->nombre }}</strong></td>
                        <td data-label="Email">{{ $solicitud->email }}</td>
                        <td data-label="Telefono">{{ $solicitud->telefono ?: '-' }}</td>
                        <td data-label="Mensaje">{{ \Illuminate\Support\Str::limit($solicitud->mensaje, 80) ?: 'Sin mensaje' }}</td>
                        <td data-label="Estado">
                            <span class="solicitud-estado solicitud-estado--{{ $solicitud->estado }}">{{ $solicitud->estado_nombre }}</span>
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
                        <td colspan="7" class="tabla-admin-vacia">Este producto todavía no tiene solicitudes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
