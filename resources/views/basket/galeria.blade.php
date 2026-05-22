{{-- LAYOUT PRINCIPAL --}}
@extends('layouts.app')

{{-- TÍTULO DE LA PESTAÑA --}}
@section('title', 'Inicio - Bellreguard Club de Basket')

{{-- CONTENIDO DE LA PÁGINA --}}
@section('contenido')

<section class="seccion-galeria-header">
    <div class="header-contenido">
        <h1>Galería Bellreguard CB</h1>
        <p>Revive los mejores momentos de nuestro equipo</p>
        <div class="stats-galeria">
            <span><i class="fas fa-camera"></i> {{ $galerias->count() }} Fotos</span>
            <span><i class="fas fa-images"></i> Galería del club</span>
        </div>
    </div>
</section>

<section class="contenedor-galeria">
    <div class="rejilla-albumes">
        @forelse($galerias as $foto)
            <div class="tarjeta-album">
                <div class="imagen-album">
                    @if($foto->image)
                        <img src="{{ $foto->image_url }}" alt="{{ $foto->titulo }}">
                    @else
                        <img src="{{ asset('img/basket.jpeg') }}" alt="{{ $foto->titulo }}">
                    @endif
                </div>
                <div class="info-album">
                    <h3>{{ $foto->titulo }}</h3>
                    <p>{{ $foto->descripcion }}</p>
                    <div class="footer-album">
                        <span class="fecha-album">{{ $foto->fecha_imagen ? $foto->fecha_imagen->translatedFormat('d F Y') : 'Sin fecha' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="tarjeta-album">
                <div class="info-album">
                    <h3>Sin fotos disponibles</h3>
                    <p>Cuando el administrador suba imágenes a la galería, aparecerán aquí automáticamente.</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

@endsection
