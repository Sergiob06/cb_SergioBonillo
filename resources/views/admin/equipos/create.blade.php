@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <h2>Crear Nuevo Equipo</h2>
    <a href="{{ route('equipos.index') }}" class="btn-nuevo" style="background: #333;">Volver</a>
</header>

{{-- Bloque para mostrar errores de validación --}}
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
    <form action="{{ route('equipos.store') }}" method="POST" enctype="multipart/form-data" class="formulario-admin">
        @csrf
        <div class="grid-formulario">
            <div class="campo">
                <label>Nombre del Equipo</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Senior A" required>
            </div>
            <div class="campo">
                <label>Categoría</label>
                <select name="category_id" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="campo">
                <label>Descripción</label>
                <textarea name="descripcion" placeholder="Breve descripción del equipo">{{ old('descripcion') }}</textarea>
            </div>

            <div class="campo campo-checkbox">
                <label>¿Es del club?</label>
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="es_local" id="es_local" value="1" {{ old('es_local') ? 'checked' : '' }}>
                    <span>Sí, es equipo local</span>
                </div>
            </div>

            <div class="campo">
                <label>Escudo/Imagen del Club</label>
                <input type="file" name="imagen_club">
            </div>
        </div>
        <div class="form-acciones">
            <button type="submit" class="btn-guardar">Guardar Equipo</button>
        </div>
    </form>
</div>
@endsection
