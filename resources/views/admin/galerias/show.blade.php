@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Detalle de Foto</h2>
        <p style="color: #777;">Vista previa del contenido de la galería</p>
    </div>
    <a href="{{ route('galerias.index') }}" class="btn-nuevo" style="background-color: #777;"><i class="fas fa-arrow-left"></i> Volver al listado</a>
</header>

<div class="pizarra-admin">
    <div class="admin-detail-layout admin-detail-layout--media">
        <div class="admin-detail-media">
            <img src="{{ $galeria->image_url }}" alt="{{ $galeria->titulo }}" style="width: 100%; max-width: 420px; border-radius: 10px; object-fit: cover;">
        </div>
        <div class="admin-detail-content">
            <h3 style="margin-top: 0;">{{ $galeria->titulo }}</h3>
            <p><strong>Categoría:</strong> {{ $galeria->categoria }}</p>
            <p><strong>Fecha:</strong> {{ $galeria->fecha_imagen ? $galeria->fecha_imagen->format('d/m/Y') : 'Sin fecha' }}</p>
            <p><strong>Descripción:</strong> {{ $galeria->descripcion }}</p>
        </div>
    </div>
</div>
@endsection
