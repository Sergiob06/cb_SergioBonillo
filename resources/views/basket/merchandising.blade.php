{{-- LAYOUT PRINCIPAL --}}
@extends('layouts.app')

{{-- TÍTULO DE LA PESTAÑA --}}
@section('title', 'Merchandising - Bellreguard Club de Basket')

{{-- CONTENIDO DE LA PÁGINA --}}
@section('contenido')
<section class="seccion-merchandising-header">
    <h1>Merchandising Oficial</h1>
    <p>Lleva los colores del Bellreguard CB contigo</p>
    <div class="iconos-merchandising">
        <i class="fa-solid fa-shirt"></i>
        <i class="fa-solid fa-hat-cap"></i>
        <i class="fa-solid fa-bag-shopping"></i>
    </div>
</section>

<section class="productos-oficiales">
    <h2>Productos Oficiales</h2>
    <p class="subtitulo"></p>

    <div class="rejilla-productos">
        @forelse($products as $product)
            <article class="tarjeta-producto tarjeta-producto-merch">
                <div class="media-producto">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" />
                    @else
                        <img src="{{ asset('img/basket.jpeg') }}" alt="{{ $product->name }}" />
                    @endif
                </div>
                <div class="info-producto">
                    <h3>{{ $product->name }}</h3>
                    <p class="descripcion-producto">{{ $product->description ?: 'Producto oficial del Bellreguard Club de Basket.' }}</p>
                    <div class="precio-comprar">
                        <span class="precio">{{ number_format((float) $product->price, 2, ',', '.') }}€</span>
                        <a href="{{ route('basket.merchandising.buy', $product) }}" class="boton-comprar">Comprar</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="tarjeta-producto tarjeta-producto-vacia" style="grid-column: 1 / -1;">
                <div class="info-producto" style="text-align: center;">
                    <h3>Proximamente</h3>
                    <p class="descripcion-producto">Todavia no hay productos publicados. Puedes crearlos desde el panel de administracion y apareceran aqui automaticamente.</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

<section class="contacto-pedido">
    <h2>¿Te gusta algun producto?</h2>
    <p>Haz clic en Comprar para enviar tu solicitud y el club te respondera por email.</p>
    @if($products->isNotEmpty())
        <a href="{{ route('basket.merchandising.buy', $products->first()) }}" class="boton-contacto">
            <i class="fa-solid fa-envelope"></i> Solicitar un producto
        </a>
    @else
        <a href="{{ route('basket.contacto') }}" class="boton-contacto">
            <i class="fa-solid fa-envelope"></i> Contactar con el club
        </a>
    @endif
</section>
@endsection
