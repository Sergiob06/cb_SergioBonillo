@extends('layouts.app')

@section('title', 'Comprar ' . $product->name . ' - Bellreguard Club de Basket')

@section('contenido')
<section class="seccion-merchandising-header">
    <h1>Comprar Producto</h1>
    <p>Envianos tu solicitud y el club se pondra en contacto contigo</p>
    <div class="iconos-merchandising">
        <i class="fa-solid fa-shirt"></i>
        <i class="fa-solid fa-envelope"></i>
        <i class="fa-solid fa-basketball"></i>
    </div>
</section>

<section class="contacto-pedido">
    <h2>{{ $product->name }}</h2>
    <p>{{ $product->description ?: 'Producto oficial del Bellreguard Club de Basket.' }}</p>

    @if(session('mensaje'))
        <div class="alerta-compra alerta-compra--ok">
            <i class="fas fa-check-circle"></i> {{ session('mensaje') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alerta-compra alerta-compra--error">
            <strong>Revisa el formulario:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="compra-grid">
        <article class="tarjeta-producto tarjeta-producto-compra">
            <div class="media-producto media-producto-compra">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" />
                @else
                    <img src="{{ asset('img/basket.jpeg') }}" alt="{{ $product->name }}" />
                @endif
            </div>
            <div class="info-producto">
                <h3>{{ $product->name }}</h3>
                <p class="descripcion-producto">{{ $product->description ?: 'Producto oficial del club.' }}</p>
                <div class="precio-comprar">
                    <span class="precio">{{ number_format((float) $product->price, 2, ',', '.') }} EUR</span>
                </div>
            </div>
        </article>

        <div class="panel-formulario-compra">
            <form action="{{ route('basket.merchandising.buy.store', $product) }}" method="POST">
                @csrf
                <div class="grid-formulario compra-formulario-grid">
                    <div class="campo">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="campo">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="campo">
                        <label for="telefono">Telefono opcional</label>
                        <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}" maxlength="30">
                    </div>

                    <div class="campo">
                        <label for="message">Mensaje opcional</label>
                        <textarea id="message" name="message" rows="6">{{ old('message') }}</textarea>
                    </div>
                </div>

                <div class="form-acciones compra-formulario-acciones">
                    <button type="submit" class="boton-contacto boton-contacto-compra" style="border: 0; cursor: pointer;">
                        <i class="fa-solid fa-paper-plane"></i> Enviar solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
