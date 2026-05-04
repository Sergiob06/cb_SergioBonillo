@extends('layouts.admin')

@section('contenido_admin')
<div class="contenedor-edit-jugador">
    <header class="header-ficha">
        <div>
            <h2>Editar Clasificación</h2>
            <p>Actualizando la fila de <strong>{{ $clasificacion->equipo_nombre }}</strong></p>
        </div>
        <a href="{{ route('clasificaciones.index') }}" class="btn-volver-link">
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
        <form action="{{ route('clasificaciones.update', $clasificacion->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.clasificaciones.partials.form', ['clasificacion' => $clasificacion])
            <div class="form-acciones-ficha">
                <a href="{{ route('clasificaciones.index') }}" class="btn-cancelar-ficha">Cancelar</a>
                <button type="submit" class="btn-actualizar-ficha"><i class="fas fa-sync-alt"></i> ACTUALIZAR FILA</button>
            </div>
        </form>
    </div>
</div>
@endsection
