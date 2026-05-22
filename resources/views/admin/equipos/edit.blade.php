@extends('layouts.admin') {{-- Carga el diseño del panel de administrador --}}

@section('contenido_admin')
<header class="header-admin">
    {{-- Mostramos el nombre del equipo dinámicamente en el título --}}
    <h2>Editar Equipo: {{ $equipo->nombre }}</h2>
</header>

@if ($errors->any())
    <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <strong>¡Vaya! Algo ha ido mal:</strong>
        {{-- Añadimos list-style: none y quitamos el padding --}}
        <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="pizarra-admin">
    {{-- IMPORTANTE: action apunta a "update" con el ID, y usamos enctype para poder subir archivos --}}
    <form action="{{ route('equipos.update', $equipo->id) }}" method="POST" enctype="multipart/form-data" class="formulario-admin">
        
        @csrf {{-- Token de seguridad para evitar ataques externos --}}
        
        @method('PUT') {{-- Los navegadores no entienden "PUT", así que Laravel lo simula aquí para la actualización --}}

        <div class="grid-formulario">
            {{-- Campo para el nombre --}}
            <div class="campo">
                <label>Nombre del Equipo</label>
                {{-- El value carga el nombre actual que ya está en la base de datos --}}
                <input type="text" name="nombre" value="{{ old('nombre', $equipo->nombre) }}" required>
            </div>

            {{-- Campo para la categoría --}}
            <div class="campo">
                <label>Categoría</label>
                <select name="category_id" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $equipo->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="campo">
                <label>Descripción</label>
                <textarea name="descripcion">{{ old('descripcion', $equipo->descripcion) }}</textarea>
            </div>

            <div class="campo campo-checkbox">
                <label>¿Es del club?</label>
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="es_local" id="es_local" value="1" {{ old('es_local', $equipo->es_local) ? 'checked' : '' }}>
                    <span>Sí, es equipo local</span>
                </div>
            </div>

            {{-- Campo para la imagen --}}
            <div class="campo">
                <label>Escudo Actual</label>
                <div class="admin-form-inline-row">
                    {{-- Mostramos la imagen que tiene el equipo ahora mismo --}}
                    <div class="admin-table-media admin-table-media--logo">
                        <img src="{{ $equipo->image_url }}" alt="Escudo de {{ $equipo->nombre }}">
                    </div>
                    
                    {{-- Input para subir una foto nueva si el usuario quiere cambiarla --}}
                    <input type="file" name="imagen_club" id="imagen_club" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml">
                </div>
                <p style="margin: 8px 0 0; font-size: 0.85em; color: #718096;">Formatos: JPG, PNG, WEBP o SVG. Si subes una nueva imagen, reemplazará la anterior.</p>
            </div>

            <div class="campo">
                <label>Usar imagen existente</label>
                <input type="text" name="imagen_existente" value="{{ old('imagen_existente') }}" placeholder="Ej: escudos/logo-equipo.png o logo-equipo.png">
                <p style="margin: 8px 0 0; font-size: 0.85em; color: #718096;">Opcional. Si subes un archivo, tendrá prioridad sobre esta ruta.</p>
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="form-acciones">
            {{-- Enlace para volver atrás sin guardar nada --}}
            <a href="{{ route('equipos.index') }}" class="admin-form-link-cancelar">Cancelar</a>
            
            {{-- El botón submit envía el formulario al método "update" del controlador --}}
            <button type="submit" class="btn-guardar">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection
