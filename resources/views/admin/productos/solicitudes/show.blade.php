@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Solicitud de producto</h2>
        <p style="color: #777;">{{ $solicitud->product?->name ?? 'Producto no disponible' }}</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        @if($solicitud->product)
            <a href="{{ route('productos.solicitudes.index', $solicitud->product) }}" class="btn-nuevo" style="background-color: #777;"><i class="fas fa-arrow-left"></i> Solicitudes del producto</a>
        @endif
        <a href="{{ route('productos.index') }}" class="btn-nuevo" style="background-color: #555;"><i class="fas fa-box"></i> Productos</a>
    </div>
</header>

@if(session('mensaje'))
    <div style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> {{ session('mensaje') }}
    </div>
@endif

<div class="pizarra-admin">
    <div class="solicitud-admin-grid">
        <div class="solicitud-admin-card">
            <h3>Datos del cliente</h3>
            <p><strong>Nombre:</strong> {{ $solicitud->nombre }}</p>
            <p><strong>Email:</strong> <a href="mailto:{{ $solicitud->email }}">{{ $solicitud->email }}</a></p>
            <p><strong>Telefono:</strong> {{ $solicitud->telefono ?: 'No indicado' }}</p>
            <p><strong>Fecha:</strong> {{ $solicitud->created_at?->format('d/m/Y H:i') }}</p>
            <p>
                <strong>Estado:</strong>
                <span class="solicitud-estado solicitud-estado--{{ $solicitud->estado }}">{{ $solicitud->estado_nombre }}</span>
            </p>
        </div>

        <div class="solicitud-admin-card">
            <h3>Producto solicitado</h3>
            @if($solicitud->product)
                <div class="solicitud-producto-resumen">
                    <img src="{{ $solicitud->product->image_url ?: asset('img/basket.jpeg') }}" alt="{{ $solicitud->product->name }}">
                    <div>
                        <strong>{{ $solicitud->product->name }}</strong>
                        <p>{{ number_format((float) $solicitud->product->price, 2, ',', '.') }} EUR</p>
                    </div>
                </div>
            @else
                <p>El producto asociado ya no está disponible.</p>
            @endif
        </div>
    </div>

    <div class="solicitud-admin-card solicitud-admin-card--full">
        <h3>Mensaje</h3>
        <p>{{ $solicitud->mensaje ?: 'El cliente no añadió mensaje adicional.' }}</p>
    </div>

    <div class="solicitud-admin-card solicitud-admin-card--full">
        <h3>Cambiar estado</h3>
        <form action="{{ route('productos.solicitudes.estado', $solicitud) }}" method="POST" class="solicitud-estado-form">
            @csrf
            @method('PATCH')

            <select name="estado" class="input-ficha" required>
                @foreach(\App\Models\ProductoSolicitud::ESTADOS as $estado)
                    <option value="{{ $estado }}" {{ $solicitud->estado === $estado ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $estado)) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-actualizar-ficha" style="margin: 0;">
                <i class="fas fa-sync-alt"></i> Actualizar estado
            </button>
        </form>
    </div>
</div>
@endsection
