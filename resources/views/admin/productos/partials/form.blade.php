<div class="grid-formulario-fichas">
    <div class="seccion-form-ficha seccion-deportiva">
        <h3><i class="fas fa-bag-shopping"></i> Datos del Producto</h3>

        <div class="campo-ficha">
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name', $product?->name) }}" required class="input-ficha">
        </div>

        <div class="campo-ficha">
            <label>Precio</label>
            <input type="number" name="price" value="{{ old('price', $product?->price) }}" step="0.01" min="0" required class="input-ficha">
        </div>
    </div>

    <div class="seccion-form-ficha seccion-personal">
        <h3><i class="fas fa-align-left"></i> Descripcion</h3>

        <div class="campo-ficha">
            <label>Descripcion del producto</label>
            <textarea name="description" class="input-ficha" style="min-height: 180px;">{{ old('description', $product?->description) }}</textarea>
        </div>
    </div>

    <div class="seccion-foto-ficha">
        <div class="admin-form-upload-media" style="text-align: center;">
            <label>Imagen actual</label>
            <div class="preview-foto">
                @if($product?->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                @else
                    <img src="{{ asset('img/basket.jpeg') }}" alt="Imagen por defecto">
                @endif
            </div>
        </div>

        <div class="admin-form-upload-field" style="flex: 1;">
            <label>{{ $product ? 'Subir nueva imagen' : 'Subir imagen' }}</label>
            <input type="file" name="image" class="input-ficha" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" style="background: white;">
            <p style="margin: 10px 0 0; font-size: 0.8em; color: #718096;">Formatos permitidos: JPG, PNG, WEBP o SVG.</p>
        </div>
    </div>
</div>
