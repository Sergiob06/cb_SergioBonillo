@extends('layouts.admin')

@section('contenido_admin')
<div class="contenedor-edit-jugador">
    <header class="header-ficha">
        <div>
            <h2>Añadir Estadística</h2>
            <p>Introduce los datos de rendimiento del equipo</p>
        </div>
        <a href="{{ route('estadisticas.index') }}" class="btn-nuevo" style="background-color: #777;">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
    </header>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>¡Vaya! Algo ha ido mal:</strong>
            <ul style="margin-top: 5px; margin-bottom: 0; list-style: none; padding-left: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pizarra-ficha">
        <form action="{{ route('estadisticas.store') }}" method="POST">
            @csrf

            @include('admin.estadisticas.partials.form', ['estadistica' => null])

            <div class="form-acciones-ficha">
                <a href="{{ route('estadisticas.index') }}" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha" style="background: #023e8a; box-shadow: 0 4px 6px rgba(2, 62, 138, 0.3);">
                    <i class="fas fa-save"></i> GUARDAR ESTADÍSTICA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
