@extends('layouts.admin')

@section('contenido_admin')
<div class="contenedor-edit-jugador">
    <header class="header-ficha">
        <div>
            <h2>Editar Estadística</h2>
            <p>Actualizando los datos de <strong>{{ $estadistica->equipo->nombre ?? 'equipo' }}</strong> en {{ $estadistica->temporada }}</p>
        </div>
        <a href="{{ route('estadisticas.index') }}" class="btn-volver-link">
            <i class="fas fa-chevron-left"></i> Volver al listado
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
        <form action="{{ route('estadisticas.update', $estadistica->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.estadisticas.partials.form', ['estadistica' => $estadistica])

            <div class="form-acciones-ficha">
                <a href="{{ route('estadisticas.index') }}" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha">
                    <i class="fas fa-sync-alt"></i> ACTUALIZAR ESTADÍSTICA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
