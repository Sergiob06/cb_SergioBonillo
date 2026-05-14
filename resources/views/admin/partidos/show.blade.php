@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Ficha del Partido</h2>
        <p style="color: #777;">Detalle del encuentro programado</p>
    </div>

    <a href="{{ route('partidos.index') }}" class="btn-nuevo" style="background-color: #777;">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
</header>

<div class="pizarra-admin">
    <div class="admin-detail-layout admin-detail-layout--match">
        <div class="admin-detail-panel admin-detail-panel--compact">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fb8500; display: inline-block;">Encuentro</h3>

            <p style="font-size: 1.2rem; margin: 20px 0 10px;">
                <strong>{{ $partido->equipoLocal->nombre ?? $partido->equipo_local }}</strong>
            </p>
            <p style="margin: 0 0 10px; color: #777;">vs</p>
            <p style="font-size: 1.2rem; margin: 0;">
                <strong>{{ $partido->equipoVisitante->nombre ?? $partido->equipo_visitante }}</strong>
            </p>
        </div>

        <div class="admin-detail-content">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #fb8500; display: inline-block;">Información General</h3>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Fecha y hora:</strong> {{ $partido->fecha_partido->format('d/m/Y H:i') }}
            </p>

            <p style="font-size: 1.1rem; margin: 15px 0;">
                <strong>Lugar:</strong> {{ $partido->lugar }}
            </p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <div class="admin-detail-actions">
                <a href="{{ route('partidos.edit', $partido->id) }}" class="btn-accion editar" title="Editar" style="margin: 0;">
                    <i class="fas fa-pen"></i>
                </a>

                <form action="{{ route('partidos.destroy', $partido->id) }}" method="POST" onsubmit="return confirm('¿Eliminar partido?')" class="admin-detail-inline-form" style="margin: 0; display: flex; align-items: center;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-accion borrar" title="Eliminar" style="margin: 0;">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
