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
    <div style="display: flex; gap: 30px; padding: 20px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px;">
            <img src="{{ $product->image_url ?: asset('img/basket.jpeg') }}" alt="{{ $product->name }}" style="width: 100%; max-width: 420px; border-radius: 10px; object-fit: cover;">
        </div>
        <div style="flex: 1; min-width: 280px;">
            <h3 style="margin-top: 0;">{{ $product->name }}</h3>
            <p><strong>Precio:</strong> {{ number_format((float) $product->price, 2, ',', '.') }} EUR</p>
            <p><strong>Descripcion:</strong> {{ $product->description ?: 'Sin descripcion disponible.' }}</p>
            <p><strong>Creado:</strong> {{ $product->created_at?->format('d/m/Y') }}</p>
        </div>
    </div>
</div>
@endsection
