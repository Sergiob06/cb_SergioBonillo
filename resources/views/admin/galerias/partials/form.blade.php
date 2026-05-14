<div class="grid-formulario-fichas">
    <div class="seccion-form-ficha seccion-deportiva">
        <h3><i class="fas fa-camera"></i> Datos de la Foto</h3>

        <div class="campo-ficha">
            <label>Título</label>
            <input type="text" name="titulo" value="{{ old('titulo', $galeria?->titulo) }}" required class="input-ficha">
        </div>

        <div class="campo-ficha">
            <label>Categoría</label>
            <input type="text" name="categoria" value="{{ old('categoria', $galeria?->categoria) }}" placeholder="Ej: Partidos, Equipo, Eventos..." required class="input-ficha">
        </div>

        <div class="campo-ficha">
            <label>Fecha</label>
            <input type="date" name="fecha_imagen" value="{{ old('fecha_imagen', $galeria?->fecha_imagen?->format('Y-m-d')) }}" class="input-ficha">
        </div>
    </div>

    <div class="seccion-form-ficha seccion-personal">
        <h3><i class="fas fa-align-left"></i> Descripción</h3>

        <div class="campo-ficha">
            <label>Descripción pequeña</label>
            <textarea name="descripcion" required class="input-ficha" style="min-height: 180px;">{{ old('descripcion', $galeria?->descripcion) }}</textarea>
        </div>
    </div>

    <div class="seccion-foto-ficha">
        <div class="admin-form-upload-media" style="text-align: center;">
            <label>Imagen actual</label>
            <div class="preview-foto">
                @if($galeria?->image)
                    <img src="{{ $galeria->image_url }}" alt="{{ $galeria->titulo }}">
                @else
                    <img src="{{ asset('img/basket.jpeg') }}" alt="Imagen por defecto">
                @endif
            </div>
        </div>

        <div class="admin-form-upload-field" style="flex: 1;">
            <label>{{ $galeria ? 'Subir nueva imagen' : 'Subir imagen' }}</label>
            <input type="file" name="imagen" class="input-ficha" style="background: white;">
            <p style="margin: 10px 0 0; font-size: 0.8em; color: #718096;">Formatos permitidos: JPG o PNG.</p>
        </div>
    </div>
</div>
